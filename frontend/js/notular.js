/*global angular,io*/
var notular=angular.module("notular",["toaster"]).run(function(socket,notification,toaster){
    if(notification==undefined)return;notification.requestPermission();
    socket.on("push",function(data){
        new notification("new notification!",{"body":data.message,tag: "codecubeNotification"});
        toaster.pop({body: data.message, title:"new notification!",timeout:-1});
    });
});
// http://www.html5rocks.com/en/tutorials/frameworks/angular-websockets/
notular.factory('notification',function($window){
    if("Notification" in $window)return $window.Notification;
    else return undefined;
});
notular.factory('socket', function ($rootScope) {
    var socket = io('wss://pusher.azurewebsites.net/push');
    return {
        on:function(eventName,callback){socket.on(eventName,function(){var args=arguments;$rootScope.$apply(function(){callback.apply(socket, args);});});},
        emit:function(eventName,data,callback){socket.emit(eventName,data,function(){var args=arguments;$rootScope.$apply(function(){if(callback){callback.apply(socket,args);}});})}
    };
});
