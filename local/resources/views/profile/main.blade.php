<?php use App\Config; ?>

<!DOCTYPE html>
<html ng-app="mainApp" >
	<head>
		@include('partials.header')
	</head>
	
	<body ng-cloak ng-controller="MainController">
		@include('partials.menubar', ['active' => 'profile'])
        <div class="container" ng-controller="UserController as UserCtrl">
        
            <div class="row" ng-init="UserCtrl.getUserInfo('{{$user}}'); UserCtrl.getUserContest('{{$user}}')" ng-controller="MessageController as MsgCtrl">
                <div class="col-md-8">

                    @if(Auth::check())
                    @if(Auth::user()->username != $user && $user != '')
                    <span class="btn btn-info pull-right" data-toggle="modal" data-target="#writeMessage" ><i class="fa fa-send"></i> ส่งข้อความ</span>

                    <div class="modal fade" id="writeMessage" aria-hidden="true" ng-controller="MessageController as MsgCtrl">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <div class="modal-title">
                                        ส่งข้อความถึง @{{UserCtrl.userInfo.display}}
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <text-angular ng-model="MsgCtrl.newMsg"></text-angular>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">ยกเลิก</button>
                                    <button type="submit" class="btn btn-primary pull-right" ng-click="MsgCtrl.sendMessage('{{$user}}', MsgCtrl.newMsg)">ส่งข้อความ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif

                    <h2><i class="fa fa-user"></i> ข้อมูลทั่วไป</h2>
                    <hr>

                    <div class="row">
                        <div class="col-sm-3">
                            <label class="pull-right">ชื่อผู้ใช้</label>
                        </div>
                        <div class="col-sm-9">
                            @{{UserCtrl.userInfo.username}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <label class="pull-right">อีเมล</label>
                        </div>
                        <div class="col-sm-9">
                            @{{UserCtrl.userInfo.email}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <label class="pull-right">ชื่อที่ใช้แสดง</label>
                        </div>
                        <div class="col-sm-9" ng-class="getUserRatingColorClass(UserCtrl.userInfo)">
                            @{{UserCtrl.userInfo.display}}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <label class="pull-right">ระดับ</label>
                        </div>
                        <div class="col-sm-9">
                            <span ng-show="UserCtrl.contests.length != null && UserCtrl.contests.length != 0">@{{UserCtrl.userInfo.rating}}</span>
                            <span ng-show="UserCtrl.contests.length == 0" class="label label-default">UNRATED</span>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-3">
                            <label class="pull-right">รูปโปรไฟล์</label>
                        </div>
                        <div class="col-sm-9">
                            <img ng-show="UserCtrl.userInfo.image == '1'" ng-src="{{Config::root()}}/img/user/@{{UserCtrl.userInfo.username}}.jpg" style="max-width:150px; max-height:150px">
                            <img ng-show="UserCtrl.userInfo.image != '1'" ng-src="{{Config::root()}}/img/user/0.jpg" style="max-width:150px; max-height:150px">
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-sm-3">
                            <label class="pull-right">ธีมโค้ด</label>
                        </div>
                        <div class="col-sm-9">
                            <h4>@{{UserCtrl.userInfo.codestyle.name}}</h4>
                            <iframe src="@{{'../codestyle/' + UserCtrl.userInfo.codestyle.file_name}}" frameborder="0" height="154px" width="300px" style="border: dotted 3px lightgray;"></iframe>
                        </div>
                    </div>

                    <div ng-init="comparing = 0">
                        @if(Auth::check())
                        @if(Auth::user()->username != $user and $user != '')
                        <button ng-class="{active: comparing == 1}" class="pull-right btn btn-info" ng-click="UserCtrl.getCompareRatingChart('{{$user}}'); comparing = (comparing + 1) % 2">เปรียบเทียบ</button>
                        @endif
                        @endif
                        <h2><i class="fa fa-line-chart"></i> ระดับผู้ใช้</h2>
                        <div style="width:99%" ng-show="comparing == 0"><canvas id="canvas" ng-init="UserCtrl.getUserRatingChart('{{$user}}')"></canvas></div>
                        @if(Auth::check())
                        @if(Auth::user()->username != $user and $user != '')
                        <div style="width:99%" ng-show="comparing == 1"><canvas id="compareRating"></canvas></div>
                        @endif
                        @endif
                        <br>
                        <br>

                    </div>

                </div>
                <div class="col-md-4">
                    @include('profile.partials.menu', ['active' => 'main', 'user' => $user])
                </div>
            </div>
            
            @if(Auth::check())
                <!-- edit info -->
                @include('profile.forms.editInfo')

                <!-- change password -->
                @include('profile.forms.changePassword')

                <!-- change img -->
                @include('profile.forms.changeImage')

                <!-- change code style -->
                @include('profile.forms.changeCodestyle')
            @endif
            
        </div>

        <toaster-container toaster-options="{'position-class': 'toast-bottom-right'}"></toaster-container>

        <footer>
            @include('partials.footer')
        </footer>
	</body>
</html>
