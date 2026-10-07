@extends('layouts.base-sidebar')

@section('content')
    <!--begin::Row-->
    <div class="row">

        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title">Edit Member - {{ $data->full_name }} - ( {{ $data->member_code}} )</h3>
                </div>
                <!-- /.card-header -->
                <form method="post">
                    @method('put')
                    @csrf
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" class="form-control" id="title" aria-describedby="title" name="title" value="{{ $old ? $old->title : $data->title }}" />
                            </div>

                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" aria-describedby="description" name="description" rows="7">{{ $old ? $old->description : $data->description }}</textarea>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="plate_number" class="form-label">Plate Number</label>
                                <input type="text" class="form-control" id="plate_number" aria-describedby="plate_number"
                                    name="plate_number" value="{{ $old ? $old->plate_number : $data->plate_number }}" />
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone_number" aria-describedby="phone_number"
                                    name="phone_number" value="{{ $old ? $old->phone_number : $data->phone_number }}" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" aria-describedby="email"
                                    name="email"
                                    value="{{ $old ? $old->email : ($data->user ? $data->user->email : null) }}" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="emergency_person" class="form-label">Emergency Person Name</label>
                                <input type="text" class="form-control" id="emergency_person"
                                    aria-describedby="emergency_person" name="emergency_person"
                                    value="{{ $old ? $old->emergency_person : $data->emergency_person }}" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="emergency_phone_number" class="form-label">Emergency Phone Number</label>
                                <input type="tel" class="form-control" id="emergency_phone_number"
                                    aria-describedby="emergency_phone_number" name="emergency_phone_number"
                                    value="{{ $old ? $old->emergency_phone_number : $data->emergency_phone_number }}" />
                            </div>

                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!--end::Row-->
@endsection
