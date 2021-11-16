<x-layout>
    <x-slot name="header">
        <h2>
            {{ __('Edit Task') }}
        </h2>
    </x-slot>

    <div>
        @if ($errors->any())
            <ul class="">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
        <form action="{{route('task.update',$task)}}" method="post">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Enter title"  value="{{ $task->title }}">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description">{{ $task->description }}</textarea>
            </div>
            <br>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <form onsubmit="return confirm('Do you really want to delete?');" action="{{route('task.destroy',$task)}}" method="post">
            @csrf
            @method('DELETE')
            <input type="submit" class="btn btn-danger" value="Delete">
        </form>


    </div>

</x-layout>

