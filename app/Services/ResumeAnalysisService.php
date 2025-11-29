<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Spatie\PdfToText\Pdf;
use Illuminate\Support\Facades\Log;
use OpenAI\Laravel\Facades\OpenAI;

class ResumeAnalysisService
{
    public function extractResumeInformation(string $fileUrl)
    {
        try {
            // Extract raw text from the resume pdf file (read pdf file, and get the text)
            $rawText = $this->extractTextFromPdf(fileUrl: $fileUrl);

            Log::debug('successfully extracted text from pdf' . strlen($rawText) . 'characters');

            // Use OpenAI API to organize the text into a structured format
            $response = OpenAI::chat()->create(
                parameters: [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are a percise resume parser. Extract information exactly as it appears in the resume without adding any interpretation or additional information. the output should be a JSON format',
                        ],
                        [
                            'role' => 'user',
                            'content' => "Parse the following resume content and extract the information as a JSON Object with the exact keys: 'summary', 'skills', 'experience', 'education'. The resume content is: " . $rawText,
                        ]
                    ],
                    'response_format' => [
                        'type' => 'json_object'
                    ],
                    'temperature' => 0.1
                ]
            );

            $result = $response->choices[0]->message->content;
            Log::debug('OpenAI response: ' . $result);

            $parsedResult = json_decode($result, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('failed to parse OpenAI response: ' . json_last_error_msg());
                throw new \Exception('failed to parse OpenAI response');
            }

            // Validate the parsed result
            $requiredKeys = ['summary', 'skills', 'experience', 'education'];
            $missingKeys = array_diff($requiredKeys, array_keys($parsedResult));

            if (count($missingKeys) > 0) {
                Log::error('Missing required keys: ' . implode(', ', $missingKeys));
                throw new \Exception('Missing required keys in the parsed result');
            }

            // Return the JSON object
            return [
                'summary'    => $parsedResult['summary'] ?? '',
                'skills'     => $parsedResult['skills'] ?? '',
                'experience' => $parsedResult['experience'] ?? '',
                'education'  => $parsedResult['education'] ?? '',
            ];
        } catch (\Exception $e) {
            Log::error('Error extracting resume information: ' . $e->getMessage());

            return [
                'summary'    => '',
                'skills'     => '',
                'experience' => '',
                'education'  => '',
            ];
        }
    }

    public function analyzeResume($jobVacancy, $resumeData)
    {
        try {
            $jobDetails = json_encode([
                'job_title'       => $jobVacancy->title,
                'job_description' => $jobVacancy->description,
                'job_location'    => $jobVacancy->location,
                'job_type'        => $jobVacancy->type,
                'job_salary'      => $jobVacancy->salary,
            ]);

            $resumeDetails = json_encode($resumeData);

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => "You are an expert HR professional and job recruiter. You are given a job vacancy and a resume.
                                    Your task is to analyze the resume and determine if the candidate is a good fit for the job.
                                    The output should be in JSON format.
                                    Provide a score from 0 to 100 for the candidate's suitability for the job, and a detailed feedback.
                                    Response should only be Json that has the following keys: 'aiGeneratedScore', 'aiGeneratedFeedback'.
                                    AI generated feedback should be detailed and specific to the job and the candidate's resume."
                    ],
                    [
                        'role' => 'user',
                        'content' => "Please evaluate this job application. Job Details: {$jobDetails}. Resume Details: {$resumeDetails}"
                    ],
                ],
                'response_format' => [
                    'type' => 'json_object'
                ],
                'temperature' => 0.1
            ]);
            $result = $response->choices[0]->message->content;
            Log::debug(message: 'OpenAI evaluation response: ' . $result);

            $parsedResult = json_decode(json: $result, associative: true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error(message: 'Failed to parse OpenAI response: ' . json_last_error_msg());
                throw new \Exception(message: 'Failed to parse OpenAI response');
            }

            if (!isset($parsedResult['aiGeneratedScore']) || !isset($parsedResult['aiGeneratedFeedback'])) {
                Log::error(message: 'Missing required keys in the parsed result');
                throw new \Exception(message: 'Missing required keys in the parsed result');
            }

            return $parsedResult;
        } catch (\Exception $e) {
            Log::error(message: 'Error analyzing resume: ' . $e->getMessage());
            return [
                'aiGeneratedScore' => 0,
                'aiGeneratedFeedback' => 'An error occurred while analyzing the resume. Please try again later.',
            ];
        }
    }



    private function extractTextFromPdf(string $fileUrl)
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'resume');

        $filePath = parse_url($fileUrl, PHP_URL_PATH);

        if (!$filePath) {
            throw new \Exception('Invalid file URL');
        }

        $filename = basename($filePath);
        $storagePath = "resumes/{$filename}";

        if (!Storage::disk('cloud')->exists($storagePath)) {
            throw new \Exception('File not found in cloud storage');
        }

        $pdfContent = Storage::disk('cloud')->get($storagePath);

        if (!$pdfContent) {
            throw new \Exception('Failed to read PDF file');
        }

        file_put_contents($tempFile, $pdfContent);

        // Possible locations for pdftotext on macOS, Linux, and Windows
        $pdfToTextPath = [
            // macOS
            '/opt/homebrew/bin/pdftotext',

            // Linux
            '/usr/bin/pdftotext',
            '/usr/local/bin/pdftotext',

            // Windows common paths
            'C:\poppler\Library\bin\pdftotext.exe',
            'C:\Program Files\poppler\Library\bin\pdftotext.exe',
            'C:\Program Files (x86)\poppler\Library\bin\pdftotext.exe',
        ];

        // Detect installed pdftotext
        $foundPath = null;

        foreach ($pdfToTextPath as $path) {
            if (file_exists($path)) {
                $foundPath = $path;
                break;
            }
        }

        // If still not found, check the PATH environment (Windows)
        if (!$foundPath) {
            $which = trim(shell_exec('where pdftotext 2>NUL'));
            if ($which && file_exists($which)) {
                $foundPath = $which;
            }
        }

        if (!$foundPath) {
            throw new \Exception('pdf-to-text is not installed on this system');
        }

        // Extract text using Spatie\PdfToText
        $instance = new Pdf($foundPath);
        $instance->setPdf($tempFile);

        $text = $instance->text();

        unlink($tempFile);

        return $text;
    }
}
