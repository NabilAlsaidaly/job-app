<x-app-layout>
    <!-- Page Header -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            {{ $jobVacancy->title }} - Apply for the job
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-black shadow-lg rounded-lg p-6 max-w-7xl mx-auto">

            <!-- Back Button -->
            <a href="{{ route('job-vacancies.show', $jobVacancy->id) }}"
                class="text-blue-400 hover:underline mb-6 inline-block">
                ← Back to Job Details
            </a>

            <!-- Job Details -->
            <div class="border-b border-white/10 pb-6">

                <div class="flex items-center justify-between">
                    <div>

                        <!-- Job Title -->
                        <h1 class="text-2xl font-bold text-white">
                            {{ $jobVacancy->title }}
                        </h1>

                        <!-- Company Name -->
                        <p class="text-md text-gray-400">
                            {{ $jobVacancy->company->name }}
                        </p>

                        <!-- Job Info -->
                        <div class="flex items-center gap-2 mt-2">

                            <p class="text-sm text-gray-400">
                                {{ $jobVacancy->location }}
                            </p>

                            <p class="text-sm text-gray-400">•</p>

                            <p class="text-sm text-gray-400">
                                ${{ number_format($jobVacancy->salary) }}
                            </p>

                            <p class="text-sm text-gray-400">•</p>

                            <p class="text-sm bg-indigo-500 text-white px-3 py-1 rounded-lg">
                                {{ $jobVacancy->type }}
                            </p>

                        </div>
                    </div>
                </div>

            </div>

            <!-- Application Form -->
            <form action="{{ route('job-vacancies.apply.process', $jobVacancy->id) }}" method="POST" class="space-y-6"
                enctype="multipart/form-data">

                @csrf

                @if ($errors->any())
                    <div class="bg-red-500 text-white p-4 rounded-lg">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!-- Resume Selection -->
                <div>
                    <h3 class="text-xl font-semibold text-white mb-4">Choose Your Resume</h3>

                    <div class="mb-6">
                        <x-input-label for="resume" value="Select from your existing resumes:" />
                        <!-- List of Resumes -->
                        <div class="space-y-4">
                            @forelse($resumes as $resume)
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="resume_option" id="{{ $resume->id }}"
                                        value="{{ $resume->id }}"
                                        @error('resume_option') class="border-red-500" @else class="border-gray-600" @enderror>
                                    <x-input-label for="existing_{{ $resume->id }}"
                                        class="text-white cursor-pointer">
                                        {{ $resume->filename }}
                                        <span class="text-gray-400 text-sm">
                                            (Last updated: {{ $resume->updated_at->format('M d, Y') }})
                                        </span>
                                    </x-input-label>
                                </div>
                            @empty
                                <span class="text-gray-400 text-sm">No resumes found.</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Upload New Resume -->
                <div x-data="{ fileName: '', hasError: {{ $errors->has('resume_file') ? 'true' : 'false' }} }">
                    <div class="flex items-center gap-2">
                        <input type="radio" x-ref="newResumeRadio" name="resume_option" id="new_resume"
                            value="new_resume"
                            @error('resume_option') class="border-red-500" @else class="border-gray-600" @enderror>
                        <x-input-label class="text-white cursor-pointer" for="new_resume"
                            value="Upload a new resume:" />
                    </div>
                    <div class="flex items-center">
                        <div class="flex-1">
                            <label for="new_resume_file" class="block text-white cursor-pointer">
                                <div class="border-2 border-dashed border-gray-600 rounded-lg p-4 hover:border-blue-500 transition"
                                    :class="{ 'border-blue-500': fileName, 'border-red-500': hasError }">
                                    <input
                                        @change="fileName = $event.target.files[0].name; $refs.newResumeRadio.checked = true"
                                        type="file" name="resume_file" id="new_resume_file" class="hidden"
                                        accept=".pdf" />
                                    <div class="text-center">
                                        <!-- Default text if no file selected -->
                                        <template x-if="!fileName">
                                            <p class="text-gray-400">Click to upload PDF (Max 5MB)</p>
                                        </template>
                                        <!-- Text when file is selected -->
                                        <template x-if="fileName">
                                            <div>
                                                <p x-text="fileName" class="mt-2 text-blue-400"></p>
                                                <p class="text-gray-400 text-sm mt-1">Click to change file</p>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-rose-600 border border-transparent rounded-lg font-semibold text-sm text-white uppercase tracking-wider hover:from-indigo-700 hover:to-rose-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-gray-800 active:from-indigo-800 active:to-rose-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">Apply
                        Now</button>
                </div>
            </form>


        </div>

    </div>
</x-app-layout>
