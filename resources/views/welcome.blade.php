<!doctype html>
<html lang="en">

    <head>
        <title>Laravel Image Intervention</title>
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <!-- Bootstrap CSS v5.2.1 -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    </head>

    <body>
        <div class="container py-4">
            <div class="row">
                <div class="col-lx-6 m-auto">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            {{-- alert response with close --}}
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @elseif (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif
                            <div class="card-header">
                                <h4 class="text-center">Image Intervention In Laravel</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="image" class="form-label">Select Image</label>
                                    <input type="file" name="image" class="form-control" id="image"
                                        placeholder="Select Image" />
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Upload</button>
                                <a href="" class="btn btn-secondary">Show Image</a>
                            </div>
                        </div>
                    </form>
                </div>
                <hr>
                <div class="col-xl-12 m-auto">
                    <h4 class="text-center">Crop Image Preview</h4>
                    @forelse ($cropImages as $crop)
                        <img src="{{ asset($crop->image) }}" width="250px" class="rounded" alt="">
                    @empty
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            No Image Found
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
            integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
            integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
        </script>
    </body>

</html>
