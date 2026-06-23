<link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}">
@vite(['resources/css/app.css', 'resources/js/app.js'])
<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.min.css">

@extends('layouts.admin')

@section('title','documentation')

@section('content')
    <section>
        <section class="top-content d-flex justify-content-between">
            <section>
                <input type="text" placeholder="Search..." id="search" name="search" class="input-search" style="text-indent: 10px">
                <button type="button" id="refresh" name="refresh" class="btn-refresh"><i class="fa-solid fa-arrows-rotate"></i></button>
            </section>
            <button type="button" name="add" id="add" class="btn-add" data-bs-toggle="modal" data-bs-target="#modalDocumentation"><i class="fa-solid fa-plus"></i> New Category</button>
        </section>
        <section class="content-body">
            <div class="table-responsive">
                <table id="datatable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Category</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </section>
    </section>

    <div class="modal fade" id="modalDocumentation" tabindex="-1" aria-labelledby="modalDocumentationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formCategory" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Category <span class="text-danger">*</span></label>
                            <select name="category" id="category" class="form-control" aria-placeholder="Choose category">
                                <option value="">Choose Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">title <span class="text-danger">*</span></label>
                            <input type="text" name="title" id="title" class="form-control" placeholder="Insert title">
                        </div>
                        <div class="form-group">
                            <label for="">Description (Optional)</label>
                            <input type="text" name="description" id="description" class="form-control" placeholder="Insert description">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn-save">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalDeleteLabel"><i class="fa-solid fa-triangle-exclamation" style="font-size: 1.5rem; color: orange;"></i> Are You Sure?</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <center>
                        <p>Category cannot be retrieve after deleted.</p>
                    </center>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn-delete-modal">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
        <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <span id="toastIcon"></span>
                <strong class="me-auto" id="toastTitle"></strong>
  
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="toastBody">
                
            </div>
        </div>
    </div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        const toast = new bootstrap.Toast(
            document.getElementById('liveToast')
        );

        const modal = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalDocumentation')
        );

        const modalDelete = bootstrap.Modal.getOrCreateInstance(
            document.getElementById('modalDelete')
        );

        let table = new DataTable('#datatable', {
            responsive: true,
            serverSide: true,
            ordering: false,
            layout: {
                topStart: null,
                topEnd: null,

                bottomStart: 'info',
                bottomEnd: 'pageLength'
            },
            ajax: "{{ route('admin.pages.category.datatable') }}",
            columns: [
                { data: "DT_RowIndex", name: "DT_RowIndex", orderable: false, searchable: false },
                { data: "name", name: "name", searchable: true },
                { data: "actions", name: "actions", orderable: false, searchable: false }
            ],
            columnDefs: [
                { orderable: false, targets: "_all" },
                { width: "5%", className: "dt-center", targets: 0 },
                { className: "dt-left", targets: 1 }, 
                { width: "20%", className: "dt-right", targets: 2 }
            ]
        });

        $(document).on('click', '#add', function() {
            $('#modalTitle').text('Add Category');
            $('#name').val(null);
        });

        $(document).on('submit', '#formCategory', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('admin.pages.category.createOrUpdate') }}",
                type: "POST",
                data: new FormData(this),
                processData: false,
                contentType: false,
                success: function(res) {
                    if(res.status === true) {
                        $('#toastTitle').text("Success");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);
                        toast.show();
                        table.ajax.reload();
                        
                        $('#id').val(null);
                        $('#name').val(null);

                        // Close modal
                        modal.hide();
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        $('body').css({
                            overflow: '',
                            paddingRight: ''
                        });
                    } else {
                        $('#toastTitle').text("Error");
                        $('#toastBody').text(res.message);
                        $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                        toast.show();
                    }
                }
            });
        });

        $(document).on('click', '#btn-edit', function() {
            let url = $(this).data('url');

            $('#id').val(null);
            $('#name').val(null);

            $('#modalTitle').text('Edit Category');
            $.ajax({
                url: url,
                type: "GET",
                success: function(res) {
                    $('#name').val(res.data.name);
                    $('#id').val(res.hashed);
                    modal.show();
                }
            });
        });

        $(document).on('click', '#btn-delete', function() {
            let url = $(this).data('url');

            modalDelete.show();

            $(document).on('click', ".btn-delete-modal", function() {
                $.ajax({
                    url: url,
                    type: "DELETE",
                    success: function(res) {
                        if(res.status == true) {
                            $('#toastTitle').text("Success");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-check" style="color: green; margin-right: 4px;"></i>`);
                            toast.show();

                            table.ajax.reload();
                            modalDelete.hide();
                        } else {
                            $('#toastTitle').text("Error");
                            $('#toastBody').text(res.message);
                            $('#toastIcon').html(`<i class="fa-solid fa-circle-xmark" style="color: red; margin-right: 4px;"></i>`);
                            toast.show();
                        }
                    }
                });
            });
        });
    });
</script>
@endsection