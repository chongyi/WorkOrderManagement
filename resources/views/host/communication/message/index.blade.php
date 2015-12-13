@extends('host.common')

@section('head')
    <script>
        var PAGE_CONFIG = {
            messageIndex: '{{ route('host.communication.message.index') }}'
        }
    </script>
@stop

@section('body')
    <div class="am-g">
        <div class="am-u-md-12">
            <div class="am-panel am-panel-default" id="message-list">
                <header class="am-panel-hd">
                    <h3 class="am-panel-title">我的消息列表</h3>
                </header>
                <div class="am-panel-bd">
                    <div class="am-btn-toolbar">
                        <div class="am-btn-group am-btn-group-xs">
                            <button class="am-btn am-btn-success" v-on:click="refresh(tab)">刷新列表</button>
                        </div>
                        <div class="am-btn-group am-btn-group-xs" data-am-button>
                            <label class="am-btn am-btn-primary am-active" v-on:click="selectTab(0)">
                                <input type="radio"> 所有消息
                            </label>
                            <label class="am-btn am-btn-primary" v-on:click="selectTab(1)">
                                <input type="radio"> 未读消息
                            </label>
                            <label class="am-btn am-btn-primary" v-on:click="selectTab(2)">
                                <input type="radio"> 已读消息
                            </label>
                        </div>
                    </div>
                </div>
                <table class="am-table am-table-striped am-table-hover">
                    <thead>
                    <tr>
                        <th>时间</th>
                        <th>标题</th>
                        <th>来源</th>
                        <th>状态</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="list.length > 0">
                        <tr v-for="item in list">
                            <td>@{{ item.send_time }}</td>
                            <td>@{{ item.title }}</td>
                            <td>@{{ item.from }}</td>
                            <td>
                                <a class="am-badge am-badge-success" v-if="item.read" v-on:click="mark(item)">已读</a>
                                <a class="am-badge am-badge-warning" v-if="!item.read" v-on:click="mark(item)">未读</a>
                            </td>
                            <td>
                                <button class="am-btn am-btn-xs am-btn-success" v-on:click="showContent(item)">查看</button>
                            </td>
                        </tr>
                    </template>
                    <template v-else>
                        <tr>
                            <td colspan="5">暂无任何消息</td>
                        </tr>
                    </template>
                    </tbody>
                </table>
                <div class="am-panel-footer">
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
                <div class="am-modal am-modal-no-btn" tabindex="-1" id="message-content-show">
                    <div class="am-modal-dialog">
                        <div class="am-modal-hd">@{{ modalData.title }}
                            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
                        </div>
                        <div class="am-modal-bd">
                            @{{{ modalData.content }}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var vueComponent = new Vue({
            el: '#message-list',
            data: {
                list: [],
                pagination: {},
                page: 1,
                selector: [
                    {list: [], pagination: {}, page: 1},
                    {list: [], pagination: {}, page: 1},
                    {list: [], pagination: {}, page: 1}
                ],
                tab: 0,
                modalData: {
                    title: '',
                    content: ''
                }
            },
            ready: function () {
                for (var i = 0; i < 3; i++) {
                    this.refresh(i);
                }
            },
            methods: {
                selectTab: function (tab) {
                    this.$set('tab', tab);
                    this.refresh(tab);
                },
                refresh: function (tab, page) {
                    if (!tab) {
                        tab = this.tab;
                    }

                    if (!page) {
                        page = this.page;
                    } else {
                        this.$set('page', page);
                        this.$set('selector[' + tab + '].page', page);
                    }

                    $.ajax({
                        url: PAGE_CONFIG.messageIndex,
                        data: {status: tab, page: page},
                        dataType: 'json',
                        success: function (response) {
                            vueComponent.$set('selector[' + tab + '].list', response.body.list);
                            vueComponent.$set('selector[' + tab + '].pagination', response.body.pagination);

                            if (tab == vueComponent.tab) {
                                vueComponent.$set('list', vueComponent.selector[tab].list);
                                vueComponent.$set('pagination', vueComponent.selector[tab].pagination);
                            }
                        }
                    });
                },
                paginate: function (event) {
                    if (event.target.tagName == 'A' || event.target.tagName == 'a') {
                        this.refresh(this.tab, event.target.getAttribute('data-page'));
                    }

                    event.preventDefault();
                },
                showContent: function (item) {
                    this.$set('modalData.title', item.title);
                    this.$set('modalData.content', item.content);

                    var modal = $('#message-content-show');
                    modal.on('opened.modal.amui', function() {
                        COMMON_METHOD.resourceUriGetter('host.communication.message.update', {id: item.id}, function(url) {
                            $.ajax({
                                url: url,
                                dataType: 'json',
                                data: {
                                    _token: COMMON_METHOD.requestTokenGetter(),
                                    status: 1
                                },
                                method: 'put',
                                success: function() {
                                    vueComponent.refresh();
                                    headerComponent.getUnreadMessageList();
                                }
                            });
                        });
                    });
                    modal.modal();
                },
                mark: function (item) {
                    COMMON_METHOD.resourceUriGetter('host.communication.message.update', {id: item.id}, function(url) {
                        $.ajax({
                            url: url,
                            dataType: 'json',
                            data: {
                                _token: COMMON_METHOD.requestTokenGetter(),
                                status: item.read ? 0 : 1
                            },
                            method: 'put',
                            success: function() {
                                vueComponent.refresh();
                                headerComponent.getUnreadMessageList();
                            }
                        });
                    });
                }
            }
        });
    </script>
@stop