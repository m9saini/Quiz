<!DOCTYPE html>
<html>
    <head>
        <style>
            #customers {
              font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
              border-collapse: collapse;
              width: 100%;
            }
            #customers td, #customers th {
              border: 1px solid #ddd;
              padding: 8px;
            }
            #customers tr:nth-child(even){background-color: #f2f2f2;}
            #customers tr:hover {background-color: #ddd;}
            #customers th {
              padding-top: 12px;
              padding-bottom: 12px;
              text-align: left;
              background-color: #4CAF50;
              color: white;
            }
            .text-center{text-align: center !important;}
        </style>
    </head>
    <body>
        <table id="customers">
          <tr align="center">
            <th colspan="7" class="text-center" >{{trans($model.'::menu.sidebar.user.title')}}</th>
          </tr>
          <tr>
             @include($model.'::export.users.user-data-fields')
          </tr>
           @each($model.'::export.users.list', $data, 'user', $model.'::export.users.user-list-empty')
        </table>
    </body>
</html>
