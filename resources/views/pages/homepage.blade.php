@extends('layouts.base-sidebar')

@section('content')
    <!--begin::Row-->
    <div class="row">
        <div class="col-12">
            <!--begin::Card-->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Starter Card</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-collapse"
                            aria-label="Collapse card">
                            <i data-lte-icon="expand" class="bi bi-plus-lg"></i>
                            <i data-lte-icon="collapse" class="bi bi-dash-lg"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-lte-toggle="card-remove"
                            aria-label="Remove card">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">Start creating your amazing application!</div>
                <!-- /.card-body -->
                <div class="card-footer">The footer of the card</div>
                <!-- /.card-footer -->
            </div>
            <!--end::Card-->
        </div>
        <!-- /.col -->
    </div>
    <!--end::Row-->
@endsection
