init_datatable();

function init_datatable(){
    $('#data_table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        paging: false,
        bInfo: false
    });
    $('#data_table2').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength : 20
    });
    $('#data_table3').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength : 20
    });
    $('#data_table4').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength : 20
    });
    $('#data_table5').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength : 20
    });
    $('#data_table6').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        pageLength : 20
    });
 
    $('#data_table_no_button').DataTable({
        dom: 'Bfrtip',
        buttons: [],
        pageLength : 20
    });

    $('.data_table').DataTable({
        dom: 'Bfrtip',
        buttons: [],
        pageLength : 20,
        searching: false,
        paging: false,
        bInfo: false,
    });

    $('.data_table1').DataTable({
        dom: 'Bfrtip',
        buttons: [],
        pageLength : 20,
        searching: false,
        paging: false,
        bInfo: false,
    });
    $('.data_table2').DataTable({
        dom: 'Bfrtip',
        buttons: [],
        pageLength : 20,
        searching: false,
        paging: false,
        bInfo: false,
    });
    $('.data_table3').DataTable({
        dom: 'Bfrtip',
        buttons: [],
        pageLength : 20,
        searching: false,
        paging: false,
        bInfo: false,
    });
    $('.data_table4').DataTable({
        dom: 'Bfrtip',
        buttons: [],
        pageLength : 20,
        searching: false,
        paging: false,
        bInfo: false,
    });

    $('#data_table_no_attribute').DataTable({
        dom: 'Bfrtip',
        buttons: [],
        paging: false,
        bInfo: false,
    });
}