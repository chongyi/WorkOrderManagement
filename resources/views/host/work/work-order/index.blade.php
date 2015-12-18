@extends('host.common')

@section('head')
    <script>
        var PAGE_CONFIG = {
            workOrderIndex: '{{ route(isset($myOrders) ? 'host.work.my-work-order.index' : 'host.work.work-order.index') }}',
            workOrderCreate: '{{ route('host.work.work-order.create') }}'
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
                            <button class="am-btn am-btn-primary" v-on:click="refresh"><i class="am-icon-refresh"></i>
                                刷新列表
                            </button>
                            <a class="am-btn am-btn-success"
                               href="{{ route('host.work.work-order.create') }}">新建工单</a>
                        </div>
                    </div>
                </div>
                <table class="am-table am-table-striped am-table-hover">
                    <thead>
                    <tr>
                        <th width="">#</th>
                        <th width="40%">工单标题</th>
                        <th width="9%">分类</th>
                        <th width="7%">发布人</th>
                        <th width="7%">状态</th>
                        <th width="17%">工单发布日期</th>
                        <th width="">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="list.length > 0">
                        <tr v-for="item in list">
                            <td>#@{{ item.id }}</td>
                            <td><a v-bind:href="item.show_url">@{{ item.subject }}</a></td>
                            <td>@{{ item.category }}</td>
                            <td>@{{ item.user }}</td>
                            <td>@{{ item.status | statusConvert }}</td>
                            <td>@{{ item.publish_time }}</td>
                            <td>
                                <a class="am-btn am-btn-xs am-btn-default" v-bind:href="item.show_url">查看</a>
                                <template v-if="item.status != 0 && item.status != 3">
                                    <button class="am-btn am-btn-xs am-btn-success" v-if="item.is_involved == true"
                                            v-on:click="off_involve" data-target="@{{ item.id }}">取消关注
                                    </button>
                                    <button class="am-btn am-btn-xs am-btn-primary" v-else="item.is_involved"
                                            v-on:click="involve" data-target="@{{ item.id }}">关注
                                    </button>
                                </template>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr>
                            <td colspan="6">暂无工单</td>
                        </tr>
                    </template>
                    </tbody>
                </table>
                <div class="am-panel-footer" v-if="list.length > 0">
                    <ul class="am-pagination">
                        <li v-if="pagination.current == 1" class="am-disabled"><a href="javascript:void(0);">&laquo;</a>
                        </li>
                        <li v-else><a href="javascript:void(0);" v-on:click="paginate"
                                      data-page="@{{ pagination.current - 1 }}">&laquo;</a></li>
                        <template v-for="page in pagination.last_page">
                            <li class="am-active" v-if="page + 1 == pagination.current"><a
                                        href="javascript:void(0);">@{{ page + 1 }}</a></li>
                            <li v-else><a href="javascript:void(0);" v-on:click="paginate"
                                          data-page="@{{ page + 1 }}">@{{ page + 1 }}</a></li>
                        </template>
                        <li v-if="pagination.current == pagination.last_page" class="am-disabled"><a
                                    href="javascript:void(0);">&raquo;</a></li>
                        <li v-else><a href="javascript:void(0);" v-on:click="paginate"
                                      data-page="@{{ pagination.current + 1 }}">&raquo;</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            Vue.filter('statusConvert', function (value) {
                return ['已关闭', '待受理', '已受理', '已解决'][value];
            });
            var vueComponment = new Vue({
                el: '#work-order-list',
                ready: function (event) {
                    this.refresh(event, 1);
                },
                methods: {
                    refresh: function (event, page) {
                        if (!page) {
                            page = 1;
                        }

                        var handle = this;
                        $.ajax({
                            url: PAGE_CONFIG.workOrderIndex,
                            type: 'get',
                            dataType: 'json',
                            data: {page: page},
                            success: function (response) {
                                handle.$set('list', response.body.list);
                                handle.$set('pagination', response.body.pagination);
                            }
                        });
                    },
                    paginate: function (event) {
                        if (event.target.tagName == 'A' || event.target.tagName == 'a') {
                            this.refresh(event, event.target.getAttribute('data-page'));
                        }

                        event.preventDefault();
                    },
                    involve: function (event, method) {
                        if (!method) {
                            method = 'put';
                        }

                        var targetId = event.target.getAttribute('data-target');

                        COMMON_METHOD.resourceUriGetter('host.communication.my-watchlist.work-order', {id: targetId}, function (url) {
                            $.ajax({
                                url: url,
                                dataType: 'json',
                                method: method,
                                data: {
                                    _token: COMMON_METHOD.requestTokenGetter()
                                },
                                success: function () {
                                    vueComponment.refresh(null, vueComponment.pagination.current);
                                }
                            });
                        });
                    },
                    off_involve: function (event) {
                        this.involve(event, 'delete');
                    }
                }
            });
        });
    </script>
@stop