@extends('host.common')

@section('head')
    <script>
        var PAGE_CONFIG = {
            workOrderIndex: '{{ route('host.work.group.work-order.index', [$enableGroup->id]) }}',
            workOrderCreate: '{{ route('host.work.group.work-order.create', [$enableGroup->id]) }}'
        }
    </script>
@stop

@section('body')
    <div class="am-g">
        <div class="am-u-md-12">
            <div class="am-panel am-panel-default" id="work-order-list">
                <header class="am-panel-hd">
                    <h3 class="am-panel-title">工单列表</h3>
                </header>
                <div class="am-panel-bd">
                    <div class="am-btn-toolbar">
                        <div class="am-btn-group am-btn-group-sm">
                            <button class="am-btn am-btn-primary" v-on:click="refresh"><i class="am-icon-refresh"></i> 刷新列表</button>
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
                    <tbody>
                        <template v-if="list.length > 0">
                            <tr v-for="item in list">
                                <td>@{{ item.id }}</td>
                                <td><a v-bind:href="item.show_url">@{{ item.subject }}</a></td>
                                <td>@{{ item.user }}</td>
                                <td>@{{ item.publish_time }}</td>
                                <td>

                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="5">暂无工单</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <div class="am-panel-footer" v-if="list.length > 0">
                    <ul class="am-pagination">
                        <li v-if="pagination.current == 1" class="am-disabled"><a href="javascript:void(0);">&laquo;</a></li>
                        <li v-else><a href="javascript:void(0);" v-on:click="paginate" data-page="@{{ pagination.current - 1 }}">&laquo;</a></li>
                        <template v-for="page in pagination.last_page">
                            <li class="am-active" v-if="page + 1 == pagination.current"><a href="javascript:void(0);">@{{ page + 1 }}</a></li>
                            <li v-else><a href="javascript:void(0);" v-on:click="paginate" data-page="@{{ page + 1 }}">@{{ page + 1 }}</a></li>
                        </template>
                        <li v-if="pagination.current == pagination.last_page" class="am-disabled"><a href="javascript:void(0);">&raquo;</a></li>
                        <li v-else><a href="javascript:void(0);" v-on:click="paginate" data-page="@{{ pagination.current + 1 }}">&raquo;</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            new Vue({
                el: '#work-order-list',
                ready: function(event) {
                    this.refresh(event, 1);
                },
                methods: {
                    refresh: function(event, page) {
                        if (!page) {
                            page = 1;
                        }

                        var handle = this;
                        $.ajax({
                            url: PAGE_CONFIG.workOrderIndex,
                            type: 'get',
                            dataType: 'json',
                            data: {page: page},
                            success: function(response) {
                                handle.$set('list', response.body.list);
                                handle.$set('pagination', response.body.pagination);
                            }
                        });
                    },
                    paginate: function(event) {
                        if (event.target.tagName == 'A' || event.target.tagName == 'a') {
                            this.refresh(event, event.target.getAttribute('data-page'));
                        }

                        event.preventDefault();
                    }
                }
            });
        });
    </script>
@stop