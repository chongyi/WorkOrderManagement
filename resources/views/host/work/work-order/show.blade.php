@extends('host.common')

@section('body')
    <div class="am-g">
        <div class="am-u-md-12">
            <div class="am-panel am-panel-primary">
                <header class="am-panel-hd">
                    <h3 class="am-panel-title">工单 #{{ $workOrder->id }}</h3>
                </header>
                <div class="am-panel-bd">
                    <section>
                        <p>{{ $workOrder->subject }}</p>
                        <small><span>创建时间 <time>{{ $workOrder->created_at->format('Y-m-d H:i:s') }}</time></span></small>
                    </section>
                    <hr>
                    <section>
                        <ul class="am-comments-list">
                            @foreach($workOrder->messages as $message)
                            <li class="am-comment @if($message->publisher->id != $workOrder->publisher->id) am-comment-flip @else am-comment-warning @endif">
                                <a href="javascript:void(0)" name="work-order-message-{{ $message->id }}">
                                    <img src="/assets/self/images/avatar.jpg" alt="" class="am-comment-avatar" width="48" height="48"/>
                                </a>
                                <div class="am-comment-main">
                                    <header class="am-comment-hd">
                                        <div class="am-comment-meta">
                                            <a href="#work-order-message-{{ $message->id }}" class="am-comment-author">{{ $message->publisher->name }}</a>
                                            发布于 <time>{{ $message->created_at->format('Y-m-d H:i:s') }}</time>
                                        </div>
                                    </header>

                                    <div class="am-comment-bd">
                                        {!! $message->content !!}
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <div class="am-g">
        <div class="am-u-md-12">
            <form class="am-form">
                <div class="am-form-group">
                    <label>发布工单消息</label>
                    <textarea class="" rows="5" id="work-order-message" name="content"></textarea>
                </div>
                <button type="submit" class="am-btn am-btn-success">发布</button>
            </form>
        </div>
    </div>
    <script src="/assets/addons/ckeditor/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('work-order-message');
        });
    </script>
@stop