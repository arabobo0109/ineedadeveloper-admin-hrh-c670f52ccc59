

<div class="col-md-12" style="padding-top: 10px;">

  <div class="light-card table-responsive">
  <h4 class="text-align-center"> Action Log</h4>
    <div class="custom-filter" style="display: none">
      <label style="margin-left: 20px">
        Action Type:
        <select id="action_type-select">
          <option value="">All</option>
          <option value="Profile">Profile</option>
          <option value="Student">Student</option>
          <option value="Room">Room</option>
          <option value="Drawer">Drawer</option>
          <option value="Fine">Fine</option>
        </select>
      </label>
    </div>
    <table class="table display companies-list" id="data-table">
      <thead>
        <tr>
          
          <th>Action Type</th>
          <th>Action Sub Type</th>
          <th>Action Content</th>
          <th>Action Date</th>
        </tr>
      </thead>
      <tbody>
        @if ( count($action_data) > 0 )
        @foreach ( $action_data as $index => $item )
        <tr class="room-record">
          
          <td>{{ $item->action_type }}</td>
          <td>{{ $item->action_sub_type }}</td>
          <td>{{ $item->action_content }}</td>
          <td>{{ $item->created_at }}</td>
        </tr>
        @endforeach
        @else
        <tr>
          <td colspan="9" class="text-center">It's empty here</td>
        </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>