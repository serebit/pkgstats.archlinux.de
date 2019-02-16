import $ from 'jquery'
import 'datatables.net'
import 'datatables.net-bs4'

$(document).ready(function () {
  const dataTable = $('#modules').DataTable({
    'lengthMenu': [25, 50, 100],
    'pageLength': 25,
    'processing': false,
    'serverSide': true,
    'order': [[1, 'desc']],
    'searchDelay': 1000,
    'pagingType': 'numbers',
    'columns': [
      {
        'data': 'name',
        'orderable': false,
        'searchable': true,
        'className': 'text-nowrap'
      },
      {
        'data': 'count',
        'orderable': true,
        'searchable': false,
        'render': function (data, type) {
          if (type === 'display') {
            let total = dataTable.page.info().recordsTotal
            if (data > total) {
              total = data
            }
            const percent = Math.ceil(data / total * 100)
            return `<div class="progress bg-transparent" title="${percent}%">
                    <div class="progress-bar bg-primary" role="progressbar"
                     style="width: ${percent}%" aria-valuenow="${percent}" aria-valuemin="0" aria-valuemax="100">
                    ${percent > 5 ? percent + ' %' : ''}
                    </div></div>`
          }
          return data
        },
        'className': 'w-75'
      }
    ]
  })
})
