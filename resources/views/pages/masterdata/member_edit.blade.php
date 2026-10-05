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
                            <div class="col-12 mb-3">
                                <label for="member_code" class="form-label">Member Code</label>
                                <input type="text" class="form-control" id="member_code"
                                    aria-describedby="member_code_help" name="member_code"
                                    value="{{ $old ? $old->member_code : $data->member_code }}" disabled/>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control" id="nik" aria-describedby="nik" name="nik" value="{{ $old ? $old->nik : $data->nik }}" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="member_firstname" class="form-label">Member First Name</label>
                                <input type="text" class="form-control" id="member_firstname"
                                    aria-describedby="member_name_help" name="first_name"
                                    value="{{ $old ? $old->first_name : $data->first_name }}" />
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="member_lastname" class="form-label">Member Last Name</label>
                                <input type="text" class="form-control" id="member_lastname"
                                    aria-describedby="member_name_help" name="last_name"
                                    value="{{ $old ? $old->last_name : $data->last_name }}" />
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="member_gender" class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value=""></option>
                                    <option value="M"
                                        {{ $old && $old->gender == 'M' ? 'selected' : ($data->gender == 'M' ? 'selected' : null) }}>
                                        Male</option>
                                    <option value="F"
                                        {{ $old && $old->gender == 'F' ? 'selected' : ($data->gender == 'F' ? 'selected' : null) }}>
                                        Women</option>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="birth_date" class="form-label">Birth Date</label>
                                <input type="date" class="form-control" id="birth_date" aria-describedby="birth_date" name="birth_date" value="{{ $old ? $old->birth_date : $data->birth_date }}" />
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


                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" aria-describedby="address" name="address" rows="7">{{ $old ? $old->address : $data->address }}</textarea>
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
