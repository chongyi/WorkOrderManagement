<!doctype html>
<html class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>WorkOrderManagement</title>

    <!-- Set render engine for 360 browser -->
    <meta name="renderer" content="webkit">

    <link rel="stylesheet" href="/assets/dep/amazeui/dist/css/amazeui.min.css">
    <link rel="stylesheet" href="/assets/self/css/host.css">
    <script src="/assets/dep/jquery/dist/jquery.min.js"></script>
    <script>
        var COMMON_METHOD = {
            resourceUriGetter: function (routeName, data, callback) {
                var base = '{{ route('host.resource-uri.getter', 'ROUTE-NAME') }}';

                $.ajax({
                    url: base.replace(/ROUTE-NAME/, routeName),
                    dataType: 'text',
                    data: data,
                    success: function (response) {
                        callback(response);
                    }
                });
            },
            requestTokenGetter: function () {
                return '{{ csrf_token() }}';
            }
        };
    </script>
    <script src="/assets/dep/vue/dist/vue.min.js"></script>
    @yield('head')
</head>
<body>
<header class="am-topbar" id="header-component">
    <div class="am-container">
        <h1 class="am-topbar-brand">
            <a href="#">WorkOrder Management</a>
        </h1>

        <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only"
                data-am-collapse="{target: '#doc-topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span
                    class="am-icon-bars"></span></button>

        <div class="am-collapse am-topbar-collapse" id="doc-topbar-collapse">
            <ul class="am-nav am-nav-pills am-topbar-nav">
                <li class="@if($currentRouteName == 'host.index')am-active @endif "><a href="{{ route('host.index') }}">首页</a>
                </li>
                <li class="am-dropdown" data-am-dropdown>
                    <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
                        工作组 <span class="am-icon-caret-down"></span>
                    </a>
                    <ul class="am-dropdown-content">
                        <li class="am-dropdown-header">可用的工作组</li>
                        @forelse($enableGroups as $group)
                            <li class="@if(isset($enableGroup) && $enableGroup->id == $group->id)am-active @endif "><a
                                        href="{{ route('host.work.group.show', [$group->id]) }}">{{ $group->display_name }}</a>
                            </li>
                        @empty
                            <li class="am-disabled"><a href="javascript:void(0);">没有可用的工作组</a></li>
                        @endforelse
                        <li class="am-divider"></li>
                        <li class="am-dropdown-header">工作组管理</li>
                        <li><a href="{{ route('host.work.group.index') }}">工作组列表</a></li>
                        <li><a href="{{ route('host.work.group.create') }}">新建一个工作组</a></li>
                    </ul>
                </li>
                @if(isset($enableGroup))
                    <li class="am-active"><a
                                href="{{ route('host.work.group.show', $enableGroup->id) }}">{{ $enableGroup->display_name }}</a>
                    </li>
                @endif
            </ul>

            <div class="am-topbar-right">
                <div class="am-dropdown" data-am-dropdown="{boundary: '.am-topbar'}">
                    <button class="am-btn am-btn-secondary am-topbar-btn am-btn-sm am-dropdown-toggle"
                            data-am-dropdown-toggle><i class="am-icon-user"></i> 您好 {{ Auth::user()->name }} <span
                                class="am-badge am-badge-warning am-round sf-float-badge" v-if="message.unread > 0"
                                v-text="message.unread"></span></button>
                    <ul class="am-dropdown-content">
                        <li><a href="{{ route('host.communication.message.index') }}">我的消息 <span
                                        class="am-badge am-badge-warning am-round" v-if="message.unread > 0"
                                        v-text="message.unread"></span></a></li>
                        <li><a href="{{ route('host.work.my-work-order.index') }}">我的工单</a></li>
                        <li class="am-divider"></li>
                        <li><a href="{{ route('host.logout') }}">注销</a></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</header>
<div class="am-container">
    @if(Session::has('create-success'))
        <div class="am-alert am-alert-success" data-am-alert>
            <button type="button" class="am-close">&times;</button>
            <p>{{ trans('common.create-success.' . Session::get('create-success')) }}</p>
        </div>
    @endif
    @yield('body')
</div>
<script src="http://cdn.staticfile.org/modernizr/2.8.3/modernizr.js"></script>
<script src="/assets/dep/amazeui/dist/js/amazeui.min.js"></script>
<script>
    var headerComponent = new Vue({
        el: '#header-component',
        data: {
            message: {
                unread: 0
            }
        },
        ready: function () {
            this.getUnreadMessageList();
            setInterval(function () {
                headerComponent.getUnreadMessageList();
            }, 10000);
        },
        methods: {
            getUnreadMessageList: function () {
                $.ajax({
                    url: '{{ route('host.communication.message.status') }}',
                    data: {unread: true},
                    dataType: 'json',
                    method: 'get',
                    success: function (response) {
                        if (headerComponent.$get('message.unread') != response.body) {
                            if (window.Notification){
                                if(Notification.permission ==='granted'){
                                    var notification = new Notification('有新的工单动态',{body:"您有新的工单，或者未读消息发生变化，请留意消息变动！"});
                                    notification.show();
                                }else {
                                    Notification.requestPermission();
                                }
                            }
                        }

                        headerComponent.$set('message.unread', response.body);
                    }
                });
            }
        }
    });
</script>
</body>
</html>