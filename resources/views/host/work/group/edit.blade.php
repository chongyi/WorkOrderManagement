@extends('host.common')

@section('body')
    <?php $edit = isset($group) ?>
    <div class="am-g">
        <div class="am-u-md-12">
            <div class="am-panel am-panel-primary">
                <header class="am-panel-hd">
                    <h3 class="am-panel-title">{{ $edit ? '编辑' : '新增' }} - 工作组</h3>
                </header>
                <div class="am-panel-bd">
                    <form class="am-form" method="post"
                          action="{{ $edit ? route('host.work.group.update', [$group->id]) : route('host.work.group.index') }}">
                        {{ csrf_field() }}
                        <div class="am-form-group">
                            <label for="group-display-name">工作组名称</label>
                            <input type="text" class="" id="group-display-name" name="display_name"
                                   placeholder="请输入工作组名称" required>
                        </div>

                        <div class="am-form-group">
                            <label for="group-description">工作组描述</label>
                            <textarea class="" rows="5" id="group-description" name="group-description"></textarea>
                        </div>

                        <button type="submit" class="am-btn am-btn-success">保存</button>
                        <a href="{{ route('host.work.group.index') }}" class="am-btn am-btn-default">取消</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop