<style>
    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(even){background-color: #f2f2f2}
</style>
<div  style="overflow-x:auto;">
    <table>
        <thead>
        <tr>
            <th>#</th>
            <th>{{__('Value')}}</th>
        </tr>
        </thead>
        <tbody>

        <tr>
            <td>{{__('ID')}}</td>
            <td>{{$result->id}}</td>
        </tr>


        <tr>
            <td>{{__('Type')}}</td>
            <td>{{$result->type}}</td>
        </tr>

        <tr>
            <td>{{__('Send To')}}</td>
            <td>{{$result->send_to}}</td>
        </tr>



        @if($result->type == 'email')

            <tr>
                <td>{{__('From Name')}}</td>
                <td>
                    {{$result->from_name}}
                </td>
            </tr>

            <tr>
                <td>{{__('From Email')}}</td>
                <td>
                    <a href="mailto:{{$result->from_email}}">{{$result->from_email}}</a>
                </td>
            </tr>


            <tr>
                <td>{{__('Subject')}}</td>
                <td>
                    {{$result->subject}}
                </td>
            </tr>


            <tr>
                <td>{{__('Body')}}</td>
                <td>
                    {!! $result->body !!}
                </td>
            </tr>

            @if($result->file)
            <tr>
                <td>{{__('File')}}</td>
                <td>
                    {{$result->file}}
                </td>
            </tr>
            @endif


        @else

            <tr>
                <td>{{__('Body')}}</td>
                <td>
                    {!! $result->body !!}
                </td>
            </tr>

        @endif



        <tr>
            <td>{{__('Status')}}</td>
            <td>
               {{$result->status}}
            </td>
        </tr>


        <tr>
            <td>{{__('Created At')}}</td>
            <td>
                @if($result->created_at == null)
                    --
                @else
                    {{$result->created_at->diffForHumans()}}
                @endif
            </td>
        </tr>

        <tr>
            <td>{{__('Updated At')}}</td>
            <td>
                @if($result->updated_at == null)
                    --
                @else
                    {{$result->updated_at->diffForHumans()}}
                @endif
            </td>
        </tr>



        <tr>
            <td>{{__('Send By')}}</td>
            <td>
                <a href="{{route('system.staff.show',$result->staff_id)}}" target="_blank">{{$result->staff->firstname}} {{$result->staff->lastname}}</a>
            </td>
        </tr>


        </tbody>
    </table>


</div>
