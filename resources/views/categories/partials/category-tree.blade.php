<div class="category-node">

    <div class="category-node-name">
        <span> {{ $category->name }} </span>
        <div class="category-actions">
            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-secondary">ویرایش</a>
            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn text-danger">حذف</button>
            </form>
        </div>
    </div>

    @if($category->children->count())

        <div class="category-node-children">

            @foreach($category->children as $child)

                @include('categories.partials.category-tree', [
                    'category' => $child
                ])

            @endforeach

        </div>

    @endif

</div>