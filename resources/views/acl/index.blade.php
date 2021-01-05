<table>
    <thead>
        <tr>
            <th>Principal Permissions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($principalPermissions->permissions as $pp)
            <tr>
                <td>{{ $pp->title }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<hr>

<table>
    <thead>
        <tr>
            <th>Administrator Permissions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($adminPermissions->permissions as $ap)
            <tr>
                <td>{{ $ap->title }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<hr>

<table>
    <thead>
        <tr>
            <th>Teacher Permissions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($teacherPermissions->permissions as $tp)
            <tr>
                <td>{{ $tp->title }}</td>
            </tr>
        @endforeach
    </tbody>
</table>