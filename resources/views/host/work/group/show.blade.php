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
                            <a class="am-btn am-btn-success"
                               href="{{ route('host.work.group.work-order.create', [$enableGroup->id]) }}">新建工单</a>
                        </div>
                    </div>
                </div>
                <table class="am-table am-table-striped am-table-hover">
                    <thead>
                    <tr>
                        <th>工单号</th>
                        <th>工单标题</th>
                        <th>工单发布人</th>
                        <th>工单发布日期</th>
                        <th>操作</th>
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
    <script>
        $(document).ready(function () {
            
        });
    </script>
@stop