@extends('layouts.app')

@section('head')
<style>
    .profile-pic {
        max-width: 150px;
        max-height: 150px;
        display: block;
    }  

    .file-upload {
        display: none;
        width: 150px;
        height: 150px;
    }
    .circle {
        border-radius: 1000px !important;
        overflow: hidden;
        width: 150px;
        height: 150px;
        border: 8px solid rgba(170, 170, 170, 0.7);
    }
    img {
        max-width: 100%;
        height: auto;
    }
    .p-image {
        position: absolute;
        width: 150px;
        height: 150px;
        color: #666666;
        transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
    }
    .p-image:hover {
        transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
    }
    .upload-button {
        font-size: 1.2em;
    }

    .upload-button:hover {
        transition: all .3s cubic-bezier(.175, .885, .32, 1.275);
        color: #999;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">

                    @if (!\Request::is('todo'))
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    @else
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="5">No</th>
                                    <th>Task</th>
                                    <th>Done</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($todo as $key => $item)
                                    <tr> 
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->task }}</td> 
                                        <td>{!! ($item->is_done) ? "<div class='badge badge-success'>Done</div>" : "<div class='badge badge-danger'>Uncomplete</div>" !!}</td>
                                        <td align="center">
                                            <div class="btn btn-group">
                                                <div class="btn btn-success"> <i class="fa fa-info"></i> </div>
                                                <div onclick="edit({{ $item->id }})" class="btn btn-primary" data-toggle="modal" data-target="#modelId"> <i class="fa fa-pencil"></i> </div>
                                                <div class="btn btn-danger"> <i class="fa fa-trash"></i> </div>
                                            </div>
                                        </td> 
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                    <form id="formData" action="" method="post">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modal title</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" id="id" name="id">
                                            <div class="form-group">
                                                <div class="row">
                                                    <label for="" class="col-md-3 control-label">Foto</label>
                                                    <div class="col-md-9 mb-3">
                                                        <div class="circle d-flex justify-content-center justify-items-center">
                                                        <!-- User Profile Image -->
                                                            <!-- Default Image -->
                                                            <img class="profile-pic" src="https://www.atlantawatershed.org/wp-content/uploads/2017/06/default-placeholder.png">
                                                        </div>
                                                        <div class="p-image">
                                                            <i class="fa fa-camera upload-button pull-right"></i>
                                                            <input id="photo" name="photo" class="file-upload form-control" accept="image/*" type="file" onblur="isValid(nama = ['photo'])" >
                                                        </div>
                                                        <span class="help-block with-errors text-danger"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="task">Task</label>
                                                <input type="text" name="task" id="task" class="form-control" placeholder="task" aria-describedby="task">
                                                <span class="help-block with-errors text-danger"></span>
                                            </div>
                                            <div class="form-group">
                                                <label for="desc">Description</label>
                                                <textarea type="text" name="desc" id="desc" class="form-control" placeholder="desciption" aria-describedby="desc"></textarea>
                                                <span class="help-block with-errors text-danger"></span>
                                            </div>
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                    <input type="checkbox" class="form-check-input" name="is_done" id="is_done" value="1">
                                                    Done
                                                </label>
                                            </div>
                                            
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Upload file process
            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('.profile-pic').attr('src', e.target.result);
                    }
            
                    reader.readAsDataURL(input.files[0]);
                }
            }
            

            $(".file-upload").on('change', function(){
                readURL(this);
            });
            
            $(".upload-button").on('click', function() {
                $(".file-upload").click();
            });
        });

        function edit(id) {
            $('#formData')[0].reset();
            $('.profile-pic').attr("src", "https://www.atlantawatershed.org/wp-content/uploads/2017/06/default-placeholder.png");
            $.ajax({
                url : window.location.origin+`/todo/${id}/edit`,
                type : "GET",
                dataType : "JSON",
                    success : function(data){
                        $('.modal-title').text('Detail Task');
                        
                        $('#id').val(data.id);
                        $('.profile-pic').attr("src", "{{ asset('img') }}"+'/'+data.image);
                        $('#task').val(data.task).attr('readonly', false);
                        $('#desc').val(data.desc).attr('disabled', false);
                        $('#is_done').attr('checked', (data.is_done) ? 'checked' : '')
                        
                    },
                    error : function(){
                        alert("Tidak dapat menampilkan data!");
                    }
            });
        }

        // Post update data
        $('#formData').on('submit', function(e){
            alert('submit');
            if(!e.isDefaultPrevented()){
                var id = $('#id').val();
                var url = "{{url('todo')}}/"+id;
                var dataForm = new FormData($(this)[0]);

                $.ajax({
                    async       : true,
                    url         : url,
                    type        : "POST",
                    data        : dataForm,
                    contentType : false,
                    cache       : false,
                    processData : false,
                    dataType    : 'JSON',
                    success     : function(data){
                        alert('send');
                        $('#modal-form').modal('hide');
                        $('#all-errors span, br').remove();
                        $('img.profile-pic').attr('src', 'https://www.atlantawatershed.org/wp-content/uploads/2017/06/default-placeholder.png');
                        window.location.reload();
                    },
                    error       : function(data){
                        alert('send');
                        var errors = data.responseJSON;
                        $.each(errors, function(e, val) {
                            var all_errors = '<span class="help-block with-errors text-danger all-errors">'+val+'</span> <br>';
                            console.log(all_errors);
                            if ($('#all-errors span') == false) {
                                $('#all-errors').append(all_errors);
                            } else {
                                $('#all-errors span, br').remove();
                                $('#all-errors').delay(1000).queue(function (next) {
                                    $(this).append(all_errors);
                                    next();
                                });
                            }
                        });
                    }   
                });
                return false;
            }
        });
    </script>
@endpush
@endsection
