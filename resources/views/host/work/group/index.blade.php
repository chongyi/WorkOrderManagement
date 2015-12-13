@extends('host.common')

@section('head')
    <script>
        var PAGE_CONFIG = {
            groupIndex: '{{ route('host.work.group.index') }}'
        }
    </script>
@stop

@section('body')
    <div class="am-g">
        <div class="am-u-md-12">
            <div class="am-panel am-panel-default" id="group-list">
                <header class="am-panel-hd">
                    <h3 class="am-panel-title">工作组列表</h3>
                </header>
                <div class="am-panel-bd">
                    <div class="am-btn-toolbar">
                        <div class="am-btn-group am-btn-group-sm">
                            <button class="am-btn am-btn-primary" v-on:click="refresh"><i class="am-icon-refresh"></i>
                                刷新列表
                            </button>
                            <a class="am-btn am-btn-success"
                               href="{{ route('host.work.group.create') }}">新建工作组</a>
                        </div>
                    </div>
                </div>
                <table class="am-table am-table-striped am-table-hover">
                    <thead>
                    <tr>
                        <th>工作组 ID</th>
                        <th>工作组名称</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="list.length > 0">
                        <tr v-for="item in list">
                            <td>@{{ item.id }}</td>
                            <td><a v-bind:href="item.show_url">@{{ item.display_name }}</a></td>
                            <td>
                                <a class="am-btn am-btn-xs am-btn-default" v-bind:href="item.show_url">查看</a>
                                <button class="am-btn am-btn-xs am-btn-success" v-if="item.is_involved == true"
                                        v-on:click="off_involve" data-target="@{{ item.id }}">取消关注
                                </button>
                                <button class="am-btn am-btn-xs am-btn-primary" v-else="item.is_involved"
                                        v-on:click="involve" data-target="@{{ item.id }}">关注
                                </button>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr>
                            <td colspan="5">没有任何工作组！<a href="{{ route('host.work.group.create') }}">创建一个工作组吧！</a></td>
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
            var vueComponment = new Vue({
                el: '#group-list',
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
                            url: PAGE_CONFIG.groupIndex,
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

                        COMMON_METHOD.resourceUriGetter('host.communication.my-watchlist.group', {id: targetId}, function (url) {
                            $.ajax({
                                url: url,
                                dataType: 'json',
                                method: method,
                                data: {
                                    _token: COMMON_METHOD.requestTokenGetter()
                                },
                                success: function() {
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