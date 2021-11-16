<x-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-5">
                <h2>
                    {{ __('Tasks') }}
                </h2>
            </div>
            <div class="col-2">
                <a class="btn btn-primary" href="{{route('task.create')}}" role="button">Create</a>
            </div>
        </div>

    </x-slot>

    <div class="section">
        @if ($errors->any())
            <ul class="">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <table class="table draggable-table" id="myTable">
            <thead>
            <tr>
                <th scope="col" >#</th>
                <th scope="col">Title</th>
                <th scope="col">priority</th>
                <th scope="col">description</th>
                <th scope="col">
                    <select onChange="filter(this.options[this.selectedIndex].value)">
                        <option value="0">All</option>
                        @foreach($projects as $project)
                            <option value="{{$project->id}}" @if($current_project && $current_project->id == $project->id ) selected  @endif>
                                {{$project->title}}
                            </option>
                        @endforeach
                    </select>
                </th>
                <th scope="col">Finished at</th>
                <th scope="col">Edit</th>
            </tr>
            </thead>
            <tbody id="sortable">
            @foreach($tasks as $task)
                <tr>
                    <td>{{$task->id}}</td>
                    <td>{{$task->title}}</td>
                    <td class="priority">{{$task->priority}}</td>
                    <td>{{$task->description}}</td>
                    <td>
                        @isset($task->project)
                            <a href="{{route('project.edit',$task->project->id)}}">{{$task->project->title}}</a>
                        @endif
                    </td>
                    <td>
                        @isset($task->finished_at)
                            <div class="row">
                                <div class="col">
                                    {{$task->finished_at}}
                                </div>
                                <div class="col">
                                    <form action="{{route('task.update',$task)}}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <input type="submit" class="btn btn-warning" value="x">
                                    </form>
                                </div>
                            </div>
                        @else
                            <form action="{{route('task.update',$task)}}" method="post">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="done" value="true">
                                <input type="submit" class="btn btn-primary" value="click to done">
                            </form>
                        @endif
                    </td>
                    <td>
                        <a href="{{route('task.edit',$task)}}">edit</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <script>

        function filter(chosen) {

            query = chosen!=0 ? "?project_id=" + chosen : "" ;
            window.location.href = "{{route('task.index')}}" + query;

        }

        Sortable.create(sortable, {
            animation: 150 ,
            onEnd: function (/**Event*/evt) {

                $('td.priority', sortable ).each(function (i) {
                    $(this).html(i+1);
                });

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                console.log(evt.oldIndex);
                console.log(evt.newIndex);
                $.ajax({
                    url:  "{{ route('task.reorder') }}" ,
                    type: 'POST',
                    data: {'oldIndex':evt.oldIndex,
                            'newIndex':evt.newIndex},
                    success: function (data) {
                    }
                });

            }
        });


    </script>
</x-layout>


