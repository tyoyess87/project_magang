@extends('layouts.app')
@section('title', 'Admin - Rooms Table')

@section('content')

    <div class="card-header">
        @if (session('flash'))
            <div class="alert alert-success alert-dismissible fade show" status="alert">
                <strong>{{ session('flash') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" status="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <h3>Rooms Table</h3>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-lg-8">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#roomCreateModal"><i class="fas fa-plus"></i> Add</button>
            </div>
            
            <div class="col-lg-4">
                <form class="form-inline my-2 my-lg-0 float-right" method="GET" action="/admin/searchroom">
                    <div class="container-searching">
                        <div class="row justify-content-center align-items-center g-2">
                            <div class="col-lg-10">
                                <input class="form-control mr-sm-2 float-end" name="search" type="search" placeholder="Search" aria-label="Search" autocomplete="off" value={{ old('search') }}>
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-primary my-2 my-sm-0 float-end" type="submit"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>              
                </form>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-hover mt-4">
                <thead>
                    <tr>
                        <th>#</th>
                        {{-- <th>ID</th> --}}
                        <th>Room Name</th>
                        <th>Room Number</th>
                        <th>Status</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Images</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                    @foreach ($room as $no => $r)
                        <tr>
                            <td scope="row">{{ $room->firstItem() + $no }}</td>
                            {{-- <td>{{ $r->id }}</td> --}}
                            <td>{{ $r->room_name }}</td>
                            <td>{{ $r->room_number }}</td>
                            <td>{{ $r->status }}</td>
                            <td>{{ $r->price }}</td>
                            <td>{{ $r->description }}</td>
                            <td>
                                @php
                                    $images = json_decode($r->image_urls, true); // Decode JSON
                                @endphp
                                @if ($images)
                                    @foreach ($images as $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="Room Image" width="100">
                                    @endforeach
                                @endif
                            </td>
                            <td>{{ $r->created_at }}</td>
                            <td>{{ $r->updated_at }}</td>
                            <td>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $r->id }}"><i class="fas fa-pencil-alt"></i></button>
                                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $r->id }}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>    
                    @endforeach
                </tbody>
            </table>
        </div>
        
        {{ $room->links() }}
    </div>
    <!-- Modal Create Room-->
    <div class="modal fade" id="roomCreateModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Create Room</h1>
            </div>
            <div class="modal-body">
                <form action="/admin/rooms" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                    <label for="room_name" class="form-label">Room Name</label>
                    <input type="text" class="form-control" id="room_name" name="room_name">
                    </div>
                    <div class="mb-3">
                    <label for="room_number" class="form-label">Room Number</label>
                    <input type="number" class="form-control" id="room_number" name="room_number">
                    </div>
                    <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price">
                    </div>
                    <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                    <label for="room_images" class="form-label">Room Images (3 images max)</label>
                    <input type="file" class="form-control" id="room_images" name="room_images[]" multiple required>
                    </div>
                    <div class="mb-3">
                    <label for='status' class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="0" selected disabled>-- Select Status --</option>
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="not_available">Not Available</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            
            <button type="submit" class="btn btn-primary">Add</button>
                </form>
            </div>
        </div>
        </div>
    </div>

    <!-- Modal Edit Room-->
    @foreach ($room as $r)
    <div class="modal fade" id="editModal{{ $r->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel{{ $r->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Room</h1>
            </div>
            <div class="modal-body">
                <form action="/admin/rooms/{{ $r->id }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                    <label for="room_name" class="form-label">Room Name</label>
                    <input type="text" class="form-control" id="room_name" name="room_name" value="{{ $r->room_name }}">
                    </div>
                    <div class="mb-3">
                    <label for="room_number" class="form-label">room_number</label>
                    <input type="number" class="form-control" id="room_number" name="room_number" value="{{ $r->room_number }}">
                    </div>
                    <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" value="{{ $r->price }}">
                    </div>
                    <div class="mb-3">
                    <label for="description" class="form-label">description</label>
                    <textarea class="form-control" id="description{{ $r->id }}" name="description" rows="3">{{ $r->description }}</textarea>
                    </div>
                    <div class="mb-3">
                    <label for="image_url{{ $r->id }}" class="form-label">Image URL</label>
                    <input type="text" class="form-control" id="image_url{{ $r->id }}" name="image_url" value="{{ $r->image_url }}">
                    </div>
                    <div class="mb-3">
                    <label for='status' class="form-label">status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="user" {{ $r->status === 'user' ? 'selected' : '' }}>user</option>
                            <option value="admin" {{ $r->status === 'admin' ? 'selected' : '' }}>admin</option>
                        </select>
                    </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            
            <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
        </div>
    </div>    
    @endforeach

    <!-- Modal Delete Room-->
    @foreach ($room as $r)
    <div class="modal fade" id="deleteModal{{ $r->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteModalLabel{{ $r->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Confirm Delete</h1>
            </div>
            <div class="modal-body">
                <form action="/admin/rooms/{{ $r->id }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="mb-3">
                    <label for="room_name" class="form-label">Room Name: <b>{{ $r->room_name }}</b></label>
                    </div>
                    <div class="mb-3">
                    <label for="room_number" class="form-label">Room Number: <b>{{ $r->room_number }}</b></label>
                    </div>
                    <div class="mb-3">
                    <label for="price" class="form-label">Price: <b>{{ $r->price }}</b></label>
                    </div>
                    <div class="mb-3">
                    <label for="description" class="form-label">Description: <b>{{ $r->description }}</b></label>
                    </div>
                    <div class="mb-3">
                    <label for='status' class="form-label">Status: <b>{{ $r->status }}</b></label>
                    </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            
            <button type="submit" class="btn btn-primary">Delete</button>
                </form>
            </div>
        </div>
        </div>
    </div>    
    @endforeach
    

@endsection