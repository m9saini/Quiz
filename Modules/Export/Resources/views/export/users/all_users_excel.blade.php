@if(!empty($data))
    <table>
        <tbody>
            <tr>
                <th colspan="10">
                    {{trans($model.'::menu.sidebar.user.title')}}
                </th>
            </tr>
            <tr>
                @include('export::export.users.user-data-fields')
            </tr>
            @each('export::export.users.list', $data, 'user', 'export::export.users.user-list-empty')
        </tbody>
    </table>
@endif