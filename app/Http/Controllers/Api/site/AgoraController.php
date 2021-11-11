<?php

namespace App\Http\Controllers\Api\site;

use App\Http\Controllers\Controller;
use App\Services\AgoraService;
use Webpatser\Uuid\Uuid;
use Exception;

class AgoraController extends Controller
{
    /**
     * @var AgoraService
     */
    protected $agoraService;

    public function __construct()
    {
        $this->agoraService = app(AgoraService::class);
    }

    public function generateToken()
    {
        try {
            $channelName = 'agora';
            // Rtc token dùng để video call

            $token = $this->agoraService->getRtcToken($channelName);
            // Rtm token dùng để chat

            $rtmToken = $this->agoraService->getRtmToken($channelName);

            if (!$token || !$rtmToken) {
                $this->error('Generate token error');
            }

            $data = [
                'channel_name' => $channelName,
                'token' => $token,
                'rtm_token' => $rtmToken,
            ];

            return response()->json($data);
        } catch (Exception $e) {
            return 'erooor';
        }
    }
}