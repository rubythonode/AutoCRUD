@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <form action="{{url('generateTable')}}" method="post" role="form">
                <div class="col-md-10 col-md-offset-1">
                    <div class="error-msg alert alert-dismissible alert-danger hidden">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Opps!</strong> <span class="msg">It seems like you select same table multipletimes.</span>
                    </div>
                </div>
                <div class="col-md-10 col-md-offset-1">
                    <div class="alert alert-dismissible alert-info">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>Heads up!</strong> Select all the fields you required even, duplicate fields from a different table.
                    </div>
                </div>
                <div class="wa">

                </div>
                <a class="btn btn-primary hidden" data-toggle="modal" href="#modal-id" id="btn_no_of_tables"></a>
                <div class="modal fade" id="modal-id">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Generate CRUD for multiple tables</h4>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="">Number of Tables</label>
                                    <select name="no_of_tables" id="no_of_tables" class="form-control">
                                        @if($size == 0)
                                            <option value="">Sorry, you have only one Table</option>
                                        @else
                                            @for($i=0; $i<$size; $i++)
                                                <option value="{{ $i + 2 }}">{{ $i + 2 }}</option>
                                            @endfor
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal" id="get_no_of_tables">OK</button>
                            </div>
                        </div><!-- /.modal-content -->
                    </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                <input type="hidden" id="options" value="{{$options}}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
            </form>
        </div>
    </div>
    </div>

@endsection

@section('custom-js')
    <script type="text/javascript">
        $(document).ready(function() {
            $(".js-example-basic-single").select2({
                placeholder: "Select a Table",
                allowClear: true
            });
            $('#get_no_of_tables').click(function()
            {
                var no_of_table = $('#no_of_tables').val();
                if(no_of_table == 2)
                {
                    $('.wa').html(
                        '<div class="col-md-4 col-md-offset-2 col-list">' +
                        '<label for="">select table</label>' +
                        '<select class="form-control tbl-list" name="slct1" onchange="getColsList(this.value,this)" required>' +
                        '<option value="">select table name</option>' +
                        $('#options').val()+
                        '</select>' +
                        '<div id="slct1"></div>'+
                        '</div>' +
                        '<div class="col-md-4 col-list">' +
                        '<label for="">select table</label>' +
                        '<select class="form-control tbl-list" name="slct2" onchange="getColsList(this.value,this)" required>' +
                        '<option value="">select table name</option>' +
                        $('#options').val()+
                        '</select>' +
                        '<div id="slct2"></div>'+
                        '<input type="hidden" value="{{ uniqid() }}" name="refid">'+
                        '<button type="submit" class="btn btn-primary btn-lg pull-right m-top validate_duplicate_table">Next</button>' +
                        '</div>'
                    );
                }
                else if(no_of_table == 3 || no_of_table == 4)
                {
                    var data = '';
                    var i = 0;
                    for(i=0; i<no_of_table; i++)
                    {
                        data = data +
                            '<div class="col-md-'+ 12/(parseInt(no_of_table))+' col-list">' +
                            '<label for="">select table</label>' +
                            '<select class="form-control tbl-list" name="slct'+ i +'" onchange="getColsList(this.value,this)" required>' +
                            '<option value="">select table name</option>' +
                            $('#options').val()+
                            '</select>' +
                            '<div id="slct'+ i +'"></div>';
                        if(i == no_of_table - 1)
                            data = data + '<button type="submit" class="btn btn-primary btn-lg pull-right m-top validate_duplicate_table">next</button>' ;
                        data = data +'</div>';
                    }
                    data = data + '<input type="hidden" value="{{ uniqid() }}" name="refid">';
                    $('.wa').html(data);
                }
                else
                {
                    var data = '';
                    for(i=0; i<no_of_table; i++)
                    {
                        data = data +
                            '<div class="col-md-2 col-list col-list">' +
                            '<label for="">select table</label>' +
                            '<select class="form-control tbl-list" name="slct'+ i +'" onchange="getColsList(this.value,this)" required>' +
                            '<option value="">select table name</option>' +
                            $('#options').val()+
                            '</select>' +
                            '<div id="slct'+ i +'"> </div>';
                        if(i == no_of_table - 1)
                            data = data + '<button type="submit" class="btn btn-primary btn-lg pull-right m-top validate_duplicate_table">next</button>' ;
                        data = data +'</div>';
                    }
                    data = data + '<input type="hidden" value="{{ uniqid() }}" name="refid">';
                    $('.wa').html(data);
                }
            });
        });

        $(document).on('change','.submit-btn','click',function() {
            return false;
        });

        {{--function getColsList(val,ele)--}}
        {{--{--}}
            {{--var clsname = ele.name;--}}
            {{--$.ajax({--}}
                {{--url: "{{ url('getColsList') }}",--}}
                {{--type:'get',--}}
                {{--data:{'table':val},--}}
                {{--success:function(data)--}}
                {{--{--}}
                    {{--var select = '';--}}
                    {{--select = select + "<div class='clearfix'></div>";--}}
                    {{--data.forEach(function(item){--}}
                        {{--select = select + '<div class="checkbox"><label><input type="checkbox" name="fields[]" value="'+ item + " " + val +'">'+ item.replace("_"," ") +'</label></div>';--}}
                    {{--});--}}
                    {{--document.getElementById(clsname).innerHTML = select;--}}
                {{--}--}}
            {{--});--}}
        {{--}--}}
    </script>
@endsection