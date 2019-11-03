@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
                    <div class="inbox_msg">
                        <div class="chat_rooms">
                            <div class="headind_srch">
                                <div class="recent_heading">
                                    <h4>@lang('Rooms')</h4>
                                </div>
                                <div class="srch_bar">
                                    <div class="stylish-input-group">
                                        <input type="text" class="search-bar" placeholder="Search">
                                        <span class="input-group-addon">
                                            <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div id ="rooms-list" class="inbox_chat">
                                @foreach($rooms as $key => $room)
                                    <div data-id={{$room->id}} class="room_list @if(empty($key))active_chat @endif">
                                        <div class="chat_room">
                                            <div class="chat_img">
                                                <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil">
                                            </div>
                                            <div class="chat_ib">
                                                <h5>{{$room->name}} <span class="chat_date">{{$room->created_at ? $room->created_at->format('M Y') : ''}}</span></h5>
                                                <p>Test</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="chat_messages">
                            <div id="msg-list" class="msg_history"></div>
                            <div class="type_msg">
                                <div class="input_msg_write">
                                    <form>
                                    <input id="chat-msg-input" type="text" placeholder="Type a message" autocomplete="off"/>
                                    <button class="chat-send-button" type="submit">
                                        <i class="fa fa-paper-plane-o" aria-hidden="true"></i>
                                    </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="inbox_people">
                            <div class="headind_srch">
                                <div class="recent_heading">
                                    <h4>@lang('People')</h4>
                                </div>
                                <div class="srch_bar">
                                    <div class="stylish-input-group">
                                        <input type="text" class="search-bar" placeholder="Search">
                                        <span class="input-group-addon">
                                            <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div id="users-list" class="inbox_chat"></div>
                        </div>
                    </div>
    </div>
</div>
@endsection
