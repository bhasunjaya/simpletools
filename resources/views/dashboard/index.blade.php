@extends('layouts.app')

@section('content')
    <main class="container">
        <div class="starter-template text-center py-5 px-3">
            <h1>Bootstrap starter template</h1>
            <p class="lead">Use this document as a way to quickly start any new project.<br> All you get is this text and a
                mostly barebones HTML document.</p>
        </div>

        <section>
            <table class="table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th style="width: 40%">Folder Type</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $page)
                        <tr>
                            <td>
                                <h4>{{ data_get($page, 'name') }}</h4>
                                <span>{{ data_get($page, 'category') }}</span>
                            </td>
                            <td>
                                <a href="{{url('/dashboard/page/' . $page['id'] . '?&token=' . $page['access_token'].'&folder=inbox')}}" class="btn btn-primary">inbox</a>
                                <a href="{{url('/dashboard/page/' . $page['id'] . '?&token=' . $page['access_token'].'&folder=other')}}" class="btn btn-primary">other</a>
                                <a href="{{url('/dashboard/page/' . $page['id'] . '?&token=' . $page['access_token'].'&folder=page_done')}}" class="btn btn-primary">done</a>
                                <a href="{{url('/dashboard/page/' . $page['id'] . '?&token=' . $page['access_token'].'&folder=spam')}}" class="btn btn-primary">spam</a>
                                
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </main>
@endsection
