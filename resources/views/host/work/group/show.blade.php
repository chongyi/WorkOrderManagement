@extends('host.common')

@section('head')
    <script>
        var PAGE_CONFIG = {
            workOrderIndex: '{{ route('host.work.group.work-order.index', [$enableGroup->id]) }}'
        }
    </script>
@stop

@section('body')
    <div class="am-g">
        <div class="am-u-md-12">
            <div class="am-panel am-panel-default">
                <header class="am-panel-hd">
                    <h3 class="am-panel-title">工单列表</h3>
                </header>
                <div class="am-panel-bd">
                    <div class="am-btn-toolbar">
                        <div class="am-btn-group am-btn-group-sm">
                            <a class="am-btn am-btn-success" href="{{ route('host.work.group.work-order.create', [$enableGroup->id]) }}">新建工单</a>
                        </div>
                    </div>
                </div>
                <table class="am-table am-table-striped am-table-hover">
                    <thead>
                    <tr>
                        <td>工单号</td>
                        <td>工单标题</td>
                        <td>工单发布人</td>
                        <td>工单发布日期</td>
                        <td>操作</td>
                    </tr>
                    </thead>
                    <tbody id="x-tpl-c-list">
                    <tr>
                        <td colspan="5">暂无工单记录</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script type="text/x-mustache-templte" id="x-tpl-list">
        @{{#list}}
        <tr>
            <td>@{{id}}</td>
            <td>@{{subject}}</td>
            <td>@{{user}}</td>
            <td>@{{publish_time}}</td>
            <td>OPERATE</td>
        </tr>
        @{{/list}}
        @{{^list}}
        <tr>
            <td colspan="5">暂无工单记录</td>
        </tr>
        @{{/list}}
    </script>
    <script>
        $(document).ready(function () {
            $.ajax({
                url: PAGE_CONFIG.workOrderIndex,
                type: 'get',
                dataType: 'json',
                success: function (response) {
                    var rendered = Mustache.render($('#x-tpl-list').html(), response.body);
                    $('#x-tpl-c-list').html(rendered);
                }
            });
        });
    </script>
@stop