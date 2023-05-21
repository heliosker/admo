<div class="table-responsive">
    <table class="table" id="tasks-table">
        <thead>
        <tr>
            <th>Name</th>
        <th>Adv Id</th>
        <th>Peak Price</th>
        <th>Is Allow Bulk</th>
        <th>Is Allow Unbind</th>
        <th>Punish</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Updated At</th>
            <th colspan="3">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($tasks as $task)
            <tr>
                <td>{{ $task->name }}</td>
            <td>{{ $task->adv_id }}</td>
            <td>{{ $task->peak_price }}</td>
            <td>{{ $task->is_allow_bulk }}</td>
            <td>{{ $task->is_allow_unbind }}</td>
            <td>{{ $task->punish }}</td>
            <td>{{ $task->status }}</td>
            <td>{{ $task->created_at }}</td>
            <td>{{ $task->updated_at }}</td>
                <td width="120">
                    {!! Form::open(['route' => ['tasks.destroy', $task->id], 'method' => 'delete']) !!}
                    <div class='btn-group'>
                        <a href="{{ route('tasks.show', [$task->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-eye"></i>
                        </a>
                        <a href="{{ route('tasks.edit', [$task->id]) }}"
                           class='btn btn-default btn-xs'>
                            <i class="far fa-edit"></i>
                        </a>
                        {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                    </div>
                    {!! Form::close() !!}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
