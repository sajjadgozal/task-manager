<x-layout>
    <x-slot name="header">
        <div class="row">
            <div class="col-5">
                <h2>
                    {{ __('Projects') }}
                </h2>
            </div>
            <div class="col-2">
                <a class="btn btn-primary" href="{{route('project.create')}}" role="button">Create</a>
            </div>
        </div>

    </x-slot>

    <div class="section">
        <table class="table draggable-table" id="myTable">
            <thead>
            <tr>
                <th scope="col">Title</th>
                <th scope="col">description</th>
                <th> </th>
            </tr>
            </thead>
            <tbody>
            @foreach($projects as $project)
                <tr>
                    <td>{{$project->title}}</td>
                    <td>{{$project->description}}</td>
                    <td>
                        <a href="{{route('project.edit',$project)}}" class="btn btn-primary">edit</a>
                        <span>/</span>
                        <form onsubmit="return confirm('Do you really want to delete?');" action="{{route('project.destroy',$project)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="submit" class="btn btn-danger" value="Delete">
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>


</x-layout>


