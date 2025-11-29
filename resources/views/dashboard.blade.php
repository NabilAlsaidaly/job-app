<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="bg-black shadow-lg rounded-lg p-6 max-w-7xl mx-auto">
            <h3 class="text-white text-2xl font-bold">
                {{ __('Welcome back,') }} {{ Auth::user()->name }}
            </h3>

            <!-- Search & Filters -->
            <div class="flex items-center justify-between mt-6">
                <form action="{{ route('dashboard') }}" method="GET" class="flex items-center justify-between">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="p-2 rounded-l-lg custom-input bg-gray-800 text-white border border-gray-700"
                        placeholder="Search for a job">
                    <button type="submit" class="bg-indigo-500 text-white p-2 rounded-r-lg">Search</button>
                    @if (request('search'))
                        <a href="{{ route('dashboard', ['search' => null]) }}"
                            class="bg-red-500 text-white px-4 py-2 rounded-lg">All</a>
                    @endif
                    @if (request('filter'))
                        <input type="hidden" name="filter" value="{{ request('filter') }}">
                    @endif
                </form>
                <div class="flex space-x-2">
                    <a href="{{ route('dashboard', ['filter' => 'Full-Time', 'search' => request('search')]) }}"
                        class="bg-indigo-500 text-white px-4 py-2 rounded-lg">Full-Time</a>
                    <a href="{{ route('dashboard', ['filter' => 'Remote', 'search' => request('search')]) }}"
                        class="bg-indigo-500 text-white px-4 py-2 rounded-lg">Remote</a>
                    <a href="{{ route('dashboard', ['filter' => 'Hybrid', 'search' => request('search')]) }}"
                        class="bg-indigo-500 text-white px-4 py-2 rounded-lg">Hybrid</a>
                    <a href="{{ route('dashboard', ['filter' => 'Contract', 'search' => request('search')]) }}"
                        class="bg-indigo-500 text-white px-4 py-2 rounded-lg">Contract</a>
                    @if (request('filter'))
                        <a href="{{ route('dashboard', ['filter' => null, 'search' => request('search')]) }}"
                            class="bg-red-500 text-white px-4 py-2 rounded-lg">All</a>
                    @endif

                </div>
            </div>

            <!-- Job List -->
            <div class="space-y-4 mt-6">
                @forelse ($jobs as $job)
                    <!-- Job Item -->
                    <div class="border-b border-white/10 pb-4 flex justify-between items-center">
                        <div>
                            <a href="{{ route('job-vacancies.show', $job->id) }}"
                                class="text-lg font-semibold text-blue-400 hover:underline">{{ $job->title }}</a>
                            <p class="text-sm text-white">{{ $job->company->name }} - {{ $job->location }}</p>
                            <p class="text-sm text-white">{{ '$' . number_format($job->salary) }} /Year</p>
                        </div>
                        <span class="bg-blue-500 text-white p-2 rounded-lg">{{ $job->type }}</span>
                    </div>
                @empty
                    <div class="text-white text-center">
                        <p>No jobs found</p>
                    </div>
                @endforelse
            </div>
            <div class="mt-6">
                {{ $jobs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
