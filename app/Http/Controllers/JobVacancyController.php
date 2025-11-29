<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobVacancy;
use App\Models\Resume;
use OpenAI\Laravel\Facades\OpenAI;
use App\Http\Requests\ApplyJobRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\JobApplication;
use App\Services\ResumeAnalysisService;

class JobVacancyController extends Controller
{
    protected $resumeAnalysisService;
    public function __construct(ResumeAnalysisService $resumeAnalysisService)
    {
        $this->resumeAnalysisService = $resumeAnalysisService;
    }

    public function show(string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        return view('job-vacancies.show', compact('jobVacancy'));
    }

    public function apply(string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $resumes = Auth::user()->resumes;
        return view('job-vacancies.apply', compact('jobVacancy', 'resumes'));
    }

    public function processApplication(ApplyJobRequest $request, string $id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        $resumeId = null;
        $extractedInfo = null;
        if ($request->input('resume_option') === 'new_resume') {
            $file = $request->file('resume_file');
            $extension = $file->getClientOriginalExtension();
            $originalFileName = $file->getClientOriginalName();
            $fileName = 'resume_' . time() . '.' . $extension;

            // Store in laravel cloud
            $path = $file->storeAs('resumes', $fileName, 'cloud');

            $fileUrl = config('filesystems.disks.cloud.url') . '/' . $path;

            $extractedinfo = $this->resumeAnalysisService->extractResumeInformation($fileUrl);


            $resume = Resume::create([
                'filename'       => $originalFileName,
                'fileUrl'        => $path,
                'userId'         => Auth::user()->id,
                'contactDetails' => json_encode([
                    'name'  => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]),

                // بيانات التحليل
                'summary'    => $extractedinfo['summary'] ?? '',
                'skills'     => json_encode($extractedinfo['skills'] ?? []),
                'experience' => json_encode($extractedinfo['experience'] ?? []),
                'education'  => json_encode($extractedinfo['education'] ?? []),
            ]);

            $resumeId = $resume->id;
        } else {
            $resumeId = $request->input('resume_option');
            $resume = Resume::findOrFail($resumeId);
            $extractedinfo = [
                'summary' => $resume->summary,
                'skills' => $resume->skills,
                'experience' => $resume->experience,
                'education' => $resume->education
            ];
        }
        //todo evaluate job application
        $evaluation = $this->resumeAnalysisService->analyzeResume($jobVacancy, $extractedinfo);
        JobApplication::create([
            'status' => 'pending',
            'jobVacancyId' => $id,
            'resumeId' => $resumeId,
            'userId' => Auth::user()->id,
            'aiGeneratedScore' => $evaluation['aiGeneratedScore'],
            'aiGeneratedFeedback' => $evaluation['aiGeneratedFeedback'],
        ]);
        return redirect()->route('job-applications.index', $id)->with('success', 'Application submitted successfully');
    }
}
