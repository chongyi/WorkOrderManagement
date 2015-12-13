@extends('host.common')

@section('body')
    <div class="am-g">
        <div class="am-u-md-12">
            <div class="am-panel am-panel-primary">
                <header class="am-panel-hd">
                    <h3 class="am-panel-title">新增工单</h3>
                </header>
                <div class="am-panel-bd">
                    @if(count($errors) > 0)
                        @foreach($errors->all() as $error)
                            <div class="am-alert am-alert-danger" data-am-alert>
                                <button type="button" class="am-close">&times;</button>
                                <p>{{ $error }}</p>
                            </div>
                        @endforeach
                    @endif

                    <form class="am-form" method="post"
                          action="{{ route('host.work.work-order.index') }}">
                        {{ csrf_field() }}
                        <div class="am-form-group">
                            <label for="work-order-subject">工单标题</label>
                            <input type="text" class="" id="work-order-subject" name="subject"
                                   placeholder="请输入工单标题" required>
                        </div>

                        <div class="am-form-group">
                            <label for="work-group">工作组</label>
                            <select name="group_id" id="work-group">
                                @foreach($groups as $group)
                                    <option value="{{ $group->id }}">{{ $group->display_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="am-form-group">
                            <label>工单分类</label>
                            <select name="category_id" id="work-order-category">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->display_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="am-form-group">
                            <label for="work-order-message">工单消息</label>
                            <textarea class="" rows="5" id="work-order-message" name="content"></textarea>
                        </div>

                        <div class="am-form-group">
                            <label for="work-order-sort">工单优先级</label>
                            <select id="work-order-sort" name="sort">
                                <option value="0">常规</option>
                                <option value="1">一级</option>
                                <option value="2">二级</option>
                                <option value="3">三级</option>
                            </select>
                        </div>

                        <button type="submit" class="am-btn am-btn-success">保存</button>
                        <a href="{{ route('host.work.my-work-order.index') }}" class="am-btn am-btn-default">取消</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/addons/ckeditor/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            CKEDITOR.replace('work-order-message');
        });
    </script>
@stop