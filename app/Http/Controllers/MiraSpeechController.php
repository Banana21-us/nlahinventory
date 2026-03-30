namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Gemini; // Use the installed SDK

class MiraSpeechController extends Controller
{
    public function processVoice(Request $request)
    {
        $audioData = $request->input('audio'); // Base64 PCM data from frontend
        
        // This is where you send the audio to Gemini 2.0 Flash
        // The SDK will return text or audio depending on your config
        $result = Gemini::model('gemini-2.0-flash')
            ->generateContent([
                'Tell Mira to respond to this audio: ',
                ['mimeType' => 'audio/wav', 'data' => $audioData]
            ]);

        return response()->json([
            'reply_text' => $result->text(),
            'reply_audio' => $result->audioContent, // If using TTS
        ]);
    }
}