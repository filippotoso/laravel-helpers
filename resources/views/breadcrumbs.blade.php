@if (!Breadcrumbs::isEmpty())
    <nav aria-label="breadcrumb" role="navigation">
        <ol class="breadcrumb">
            @foreach (Breadcrumbs::get() as $breadcrumbUrl => $breadcrumbLabel)
            <li class="breadcrumb-item"><a href="{{ $breadcrumbUrl }}">{{ $breadcrumbLabel }}</a></li>
            @endforeach
            <li class="breadcrumb-item active" aria-current="page">{{ Breadcrumbs::last() }}</li>
        </ol>
    </nav>
@endif
