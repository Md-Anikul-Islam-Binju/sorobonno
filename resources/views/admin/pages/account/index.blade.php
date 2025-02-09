@extends('admin.app')
@section('admin_content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a href="/dashboard">Sorborno</a></li>
                                <li class="breadcrumb-item active">Sorborno</li>
                            </ol>
                        </div>
                        <h4 class="page-title">Account Setting</h4>
                    </div>
                </div>
            </div>
            <!-- Form row -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="header-title">Account Information</h4>
                        </div>

                        <div class="card-body">
                            @if($user && $user->referral_code_status == 1)
                                <div class="mb-3">
                                    <label class="form-label">Registration Link</label>
                                    <div class="d-flex align-items-center">
                                        <input type="text" class="form-control" value="{{ url('/account-registration-for-user?code=' . $user->referral_code) }}" id="referralLink" readonly>
                                        <button type="button" class="btn btn-info ms-2" onclick="copyReferralLink()">Copy</button>
                                    </div>
                                </div>
                            @else
                                <p>Your affiliate link is currently inactive.</p>
                            @endif
                        </div>

                        <div class="card-body">
                            <form action="{{route('account.settings.create.update',$user ? $user->id : null)}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row g-2">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" value="{{$user?$user->name:''}}"
                                               placeholder="Enter Name English">
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" value="{{$user?$user->email:''}}"
                                               placeholder="Enter Email">
                                    </div>
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="phone" value="{{$user?$user->phone:''}}"
                                               placeholder="Enter Phone">
                                    </div>


                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Address</label>
                                        <input type="text" class="form-control" name="address" value="{{$user?$user->address:''}}"
                                               placeholder="Enter Address (En)">
                                    </div>

                                    <div class="mb-3 col-md-4">
                                        <label for="profile" class="form-label">Profile Image</label>
                                        <input type="file" class="form-control" name="profile" value="{{$user?$user->profile:''}}"
                                               placeholder="Enter Logo">
                                        @if($user? $user->profile:'')
                                            <img src="{{asset($user? $user->profile:'' )}}" alt="Current Image" class="mt-2" style="max-width: 50px;">
                                        @endif
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="header-title">Affiliate Information</h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('affiliate.link.create.update', $user ? $user->id : null) }}" method="post">
                                @csrf
                                <div class="row g-2">
                                    <div class="mb-3 col-md-4">
                                        <label class="form-label">Affiliate Status</label>
                                        <div class="form-check form-switch">
                                            <!-- Hidden input to send 0 when checkbox is off -->
                                            <input type="hidden" name="status" value="0">

                                            <!-- Toggle switch that sends 1 when checked -->
                                            <input class="form-check-input" type="checkbox" role="switch" name="status" value="1"
                                                   @if($user && $user->referral_code_status == 1) checked @endif id="statusToggle">
                                            <label class="form-check-label" for="statusToggle" id="statusLabel">
                                                @if($user && $user->referral_code_status == 1) On @else Off @endif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>






        </div>
    </div>

    <script>
        // Change label text dynamically when the switch is toggled
        document.getElementById('statusToggle').addEventListener('change', function() {
            var label = document.getElementById('statusLabel');
            if (this.checked) {
                label.textContent = 'On';
            } else {
                label.textContent = 'Off';
            }
        });
    </script>

    <script>
        function copyReferralLink() {
            // Get the text field
            var copyText = document.getElementById("referralLink");

            // Select the text field
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices

            // Copy the text inside the text field
            document.execCommand("copy");

            // Alert the user that the text has been copied
            alert("Referral link copied to clipboard: " + copyText.value);
        }
    </script>
@endsection
