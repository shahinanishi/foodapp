<x-layout doctitle="Upload Post Image">
    <div class="container container--narrow py-md-5">
        <h2 class="text-center mb-3">Upload Image</h2>
        <form action="/manage-image" method="POST" enctype="multipart/form-data">

            @csrf
            <div class="mb-3">
                <input type="file" name='image'>
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</x-layout>