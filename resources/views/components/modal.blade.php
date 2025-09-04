<div class="modal fade" id="{{ $modalId }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{{ $title }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ $url }}" id="{{ $formId }}" method="{{ $method }}">
                @if($method)
                    @csrf
                @endif

                @if($method == 'PUT')
                    @method('PUT')
                @endif

                @if($method == 'DELETE')
                    @method('DELETE')
                @endif

                <div class="modal-body">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="{{ $buttonId }}">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>