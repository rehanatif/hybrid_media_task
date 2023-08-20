<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <button class="btn btn-sm float-end btn-primary" onclick="onFetchFormModal(event,'{{route('add_event')}}','#md_add_event','#bind_md_add_event')">Add Event</button>
                    <br>
                    <br>
                    <hr>

                    <table class="table table-striped table-hover" id="events_table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Attendies</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
<div id="bind_md_add_event"></div>
<div id="bind_md_update_event"></div>
</x-app-layout>
@include('events.scripts.event_script')