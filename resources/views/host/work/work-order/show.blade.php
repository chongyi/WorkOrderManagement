@extends('host.common')

@section('head')
<script>
    var PAGE_CONFIG = {
        workOrderMessageIndex: '{{ route('host.work.work-order.message.index', $workOrder->id) }}'
    }
</script>
@stop

@section('body')
    <div class="am-g">
        <div class="am-u-md-12">
            <div class="am-panel am-panel-primary" id="work-order-panel">
                <header class="am-panel-hd">
                    <h3 class="am-panel-title">工单 #{{ $workOrder->id }}</h3>
                </header>
                <div class="am-panel-bd">
                    <section class="am-panel am-panel-default">
                        <div class="am-panel-bd">
                            <div class="am-btn-toolbar">
                                <div class="am-btn-group am-btn-group-xs">
                                    <button class="am-btn am-btn-primary" v-on:click="refresh"><i class="am-icon-refresh"></i>
                                        刷新列表
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="am-panel am-panel-success">
                        <div class="am-panel-bd">
                            <p>{{ $workOrder->subject }}</p>
                            <small><span>创建时间 <time>{{ $workOrder->created_at->format('Y-m-d H:i:s') }}</time></span></small>
                        </div>
                    </section>
                    <hr>
                    <section>
                        <ul class="am-comments-list">
                            <template v-for="item in list">
                                <li class="am-comment" v-bind:class="{'am-comment-flip': !item.is_publisher_message, 'am-comment-primary': item.is_publisher_message}">
                                    <a href="javascript:void(0)" name="work-order-message-@{{ item.id }}">
                                        <img src="/assets/self/images/avatar.jpg" alt="" class="am-comment-avatar" width="48" height="48"/>
                                    </a>
                                    <div class="am-comment-main">
                                        <header class="am-comment-hd">
                                            <div class="am-comment-meta">
                                                <a href="#work-order-message-@{{ item.id }}" class="am-comment-author">@{{ item.publisher }}</a>
                                                发布于 <time>@{{ item.publish_time }}</time>
                                            </div>
                                        </header>

                                        <div class="am-comment-bd">
                                            @{{{ item.content }}}
                                        </div>
                                    </div>
                                </li>
                            </template>
                        </ul>
                    </section>
                </div>
                <div class="am-panel-footer">
                    <form class="am-form">
                        <div class="am-form-group">
                            <label>发布工单消息</label>
                            <textarea class="" rows="5" id="work-order-message-content" name="content"></textarea>
                        </div>
                        <button type="button" class="am-btn am-btn-success" v-on:click="pushNewMessage">发布</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/addons/ckeditor/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            var vueComponent = new Vue({
                el: '#work-order-panel',
                ready: function() {
                    var editor = CKEDITOR.replace('work-order-message-content');

                    this.$set('editor', editor);
                    this.refresh();
                },
                methods: {
                    refresh: function() {
                        $.ajax({
                            url: PAGE_CONFIG.workOrderMessageIndex,
                            dataType: 'json',
                            success: function (response) {
                                vueComponent.$set('list', response.body.list);
                            }
                        });
                    },
                    pushNewMessage: function() {
                        var content = this.editor.getData();
                        $.ajax({
                            url: PAGE_CONFIG.workOrderMessageIndex,
                            dataType: 'json',
                            data: {
                                _token: COMMON_METHOD.requestTokenGetter(),
                                content: content
                            },
                            method: 'post',
                            success: function() {
                                vueComponent.refresh();
                            }
                        })
                    }
                }
            });
        });
    </script>
@stop