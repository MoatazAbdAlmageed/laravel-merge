@extends('layout')
@section('title')
<div class="flex">
    <div class="flex items-center">
        <div class="flex-shrink-0 h-10 w-10 mr-4">
            <img class="h-10 w-10 rounded-full" src="{{ $author->photo ?? '#' }}" alt="{{ $author->name ?? '-' }}" />
        </div>
        <h2 class="text-3xl leading-9 font-bold text-white mr-4">
            <span class="font-thin">Pull Requests Of</span>
            <span class="text-gray-500">
                <a href="{{ url('https://github.com/'.$author->name) }}" target="_blank">
                    {{ '@'.($author->name ?? '-') }}
                </a>
            </span>
        </h2>
    </div>
</div>
@endsection
@section('content')
<div class="flex flex-col">
    <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
        <table class="min-w-full bg-white">
            <thead>
            <tr>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Title
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Github
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-200"></th>
            </tr>
            </thead>
            <tbody class="bg-white">
            @foreach($pullRequests as $pr)
            <tr x-data="{ contentOpen: false }">
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 flex justify-between">
                    <div>
                        <div class="text-md leading-5 text-gray-900">
                            {{ $pr->title }}
                            @if($pr->isToday())
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                Today
                            </span>
                            @endif
                        </div>
                        <div class="text-sm leading-5 text-gray-500">{{ $pr->pr_merged_at->format('Y-m-d H:i') }}</div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                <a href="{{ url('/r/'.$pr->id) }}" target="_block" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full text-purple-500">
                    #{{ $pr->pr_id }}
                </a>
                </td>
                <td class="px-6 py-4 whitespace-no-wrap text-right border-b border-gray-200 text-sm leading-5 font-medium">
                    <a href="{{ url('/pull-request/'.$pr->id) }}">
                        <svg viewBox="0 0 24 24" width="24" height="24" class="text-gray-600 hover:text-gray-400" fill="currentColor">
                            <path d="M17.56 17.66a8 8 0 0 1-11.32 0L1.3 12.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95zm-9.9-1.42a6 6 0 0 0 8.48 0L20.38 12l-4.24-4.24a6 6 0 0 0-8.48 0L3.4 12l4.25 4.24zM11.9 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                        </svg>
                    </a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        <div class="bg-white px-4 py-3 flex items-center justify-between sm:px-6">
            <div class="flex-1 flex justify-between sm:justify-end">
                @if (!$pullRequests->onFirstPage())
                <a
                    href="{{ $pullRequests->previousPageUrl() }}"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                >
                Previous
                </a>
                @endif
                @if($pullRequests->hasMorePages())
                <a href="{{ $pullRequests->nextPageUrl() }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150">
                    Next
                </a>
                @endif
            </div>
            </div>
        </div>
    </div>
</div>
@if($pullRequests->count() > 2)
<div id="chart" class="mt-8 bg-white">
    <canvas id="weekley-pr" class="w-full" style="height:200px"></canvas>
    <script>
    var ctx = document.getElementById('weekley-pr');
    var myChart = new Chart(ctx, {
        "type":"line",
        "data":{
            "labels":["{!! $summary->keys()->implode('","') !!}"],
            "datasets":[{
                "label":"",
                "data":[{{ $summary->values()->implode(',') }}],
                "fill":false,
                "borderColor":"#2d3748",
                "lineTension":0.1
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        suggestedMax: {{ ($max = $summary->max()) + max(1,$max * 10 / 100) }}
                    }
                }]
            }
        }
    });
    </script>
</div>
@endif
@endsection