<?php

namespace App\Http\Controllers;

use App\Charts\HighCharts;
use App\EngineQueries;
use App\EngineTypes;
use App\QueriesResult;
use App\QuerieTweets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwitterEngineController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $engineQueries = new EngineQueries();
        $userQueries = $engineQueries->getMyHistory();

        return view('twitterengine')->with(['contents' => "", 'userQueries' => $userQueries]);
    }


    public function doTwitterEngine(Request $request)
    {
        $task = $request->get('config_engine');
        $keyword = $request->get('keyword');
        $ntweets = $request->get('n_tweets');

        $action = $request->get('action'); //sustituo de $var y $queries_done
        $queries_done = $request->get('queries_done'); //CASE reSendTweets ID de querie seleccionada

        $engineQueries = new EngineQueries();
        $userQueries = $engineQueries->getMyHistory();

        switch ($action) {
            case "setEnv":
                $config_ok = $this->setTwitterEngineEnv($task);
                if ($config_ok)
                    $ret = view('twitterEngine')->with(['contents' => "", 'userQueries' => $userQueries,
                        'task' => $task, 'config_ok' => $config_ok]);
                else
                    $ret = view('twitterEngine')->with(['contents' => "", 'userQueries' => $userQueries]); //CON ERROR
                break;

            case "getAndSendTweets":
                //Once again, we use file_get_contents to GET the URL in question.
                $url = env('API_URL') . env('API_GET_TWEETS') . '?maxTweets=' . $ntweets . '&amp;hash_tag_list=' . $keyword;
                //OBTENER TWEETS CON TWEEPY
                $tweets = file_get_contents($url);
                $ret = $this->doTwitterClassificationAndShow($tweets, $task, $keyword, $ntweets, $userQueries);
                break;

            case "reSendTweets":
                $tweets_done = $this->getTweetsOfDoneQuerie($queries_done);
                $tweets_done = $this->convertQuerieTweetsArray($tweets_done);
                $tweets = json_encode($tweets_done, true);
                $engineQuerie = EngineQueries::where('CM_CD_CONSULTA_MOTOR', '=', $queries_done)->first();
                $keyword = $engineQuerie->CM_PALABRA_BUSQUEDA;
                $ntweets = $engineQuerie->CM_NUMERO_TWEETS;

                $ret = $this->doTwitterClassificationAndShow($tweets, $task, $keyword, $ntweets, $userQueries);
                break;
            default:
                break;
        }

        return $ret;
    }

    public function doTwitterClassificationAndShow($tweets, $task, $keyword, $ntweets, $userQueries) {
        $tags = $this->classifyTweets($tweets);

        //GUARDAR CONSULTA en BD
        $cm_cd_consulta_motor = $this->insertConsultaMotor($task, $keyword, $ntweets);
        //TRASNFORMA JSON EN ARRAY!------------->>>>>>
        $tweets_array = json_decode($tweets, true);
        //GUARDAR TWEETS
        if ($cm_cd_consulta_motor)
            $this->saveTweets($tweets_array, $cm_cd_consulta_motor);
        else
            return view('twitterEngine')->with(['error' => 'error']);

        //CREAR GRAFICA DE RESULTADOS
        $res = $this->createHighChart($tags, $cm_cd_consulta_motor); // and insert tweets classification results

        $chart = $res[0];
        $resultados_analisis = $res[1];

        return view('twitterEngine')->with([
            'contents' => $tweets_array,
            'resultados_analisis' => $resultados_analisis,
            'chart' => $chart,
            'userQueries' => $userQueries,
            'task' => $task,
            'config_ok' => true]);
    }


    public function setTwitterEngineEnv($task)
    {


        $ch = curl_init(env('API_URL') . env('API_SET_ENV'));

        $jsonData = array(
            'task' => $task
        );

        $jsonDataEncoded = json_encode($jsonData);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }

    public function classifyTweets($tweets)
    {


        $ch = curl_init(env('API_URL') . env('API_TAG'));


        $jsonData = array(
            'tweets' => $tweets
        );
        $jsonDataEncoded = json_encode($jsonData);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }


    public function createHighChart($json_result_tweets, $cm_cd_consulta_motor)
    {

        $this->saveTagResults($json_result_tweets, $cm_cd_consulta_motor);
        $tweets_json_decoded = json_decode($json_result_tweets, true);
        $chart = new HighCharts();

        $porcentaje_confianza = [];
        $contador = [];
        $count = 1;
        $resultado_analisis = [];

        foreach ($tweets_json_decoded as $tweets_result) {
            foreach ($tweets_result as $key => $res) {
                if ($key == "conf") {
                    $porcentaje_confianza[] = (float)substr($res, 0, 5);
                    $contador[] = $count;
                    $count = $count + 1;
                } else
                    $resultado_analisis[] = $res;

            }
        }
        $chart->labels($contador);
        $chart->dataset('Consulta ' . $cm_cd_consulta_motor, 'line', $porcentaje_confianza);

        return [$chart, $resultado_analisis];
    }

    public function insertConsultaMotor($config_engine, $keyword, $n_tweets)
    {
        $id_config_engine = EngineTypes::where('TCM_VALOR', 'LIKE', $config_engine)->first();
        $engineQueries = new EngineQueries();
        $engineQueries->TCM_CD_MOTOR = $id_config_engine->TCM_CD_MOTOR;
        $engineQueries->CM_PALABRA_BUSQUEDA = $keyword;
        $engineQueries->CM_NUMERO_TWEETS = $n_tweets;
        $engineQueries->USUARIO = Auth::user()->id;
        $result = $engineQueries->save();

        if ($result)
            return $engineQueries->CM_CD_CONSULTA_MOTOR;
        else
            return null;
    }

    public function insertResultadosTag($cm_cd_consuta_motor, $num_tweet, $confianza, $prediccion)
    {
        $querieResult = new QueriesResult();
        $querieResult->CM_CD_CONSULTA_MOTOR = $cm_cd_consuta_motor;
        $querieResult->TC_NUM_TWEET = $num_tweet;
        $querieResult->RTC_CONFIANZA = $confianza;
        $querieResult->RTC_PREDICION = $prediccion;
        $result = $querieResult->save();

        if ($result)
            return true;
        else
            return false;
    }

    public function insertTweetsConsulta($cm_cd_consuta_motor, $num_tweet, $text_tweet)
    {
        $querieTweets = new QuerieTweets();
        $querieTweets->CM_CD_CONSULTA_MOTOR = $cm_cd_consuta_motor;
        $querieTweets->TC_NUMERO_TWEET = $num_tweet;
        $querieTweets->TC_TEXTO_TWEET = $text_tweet;
        $result = $querieTweets->save();

        if ($result)
            return true;
        else
            return false;
    }

    public function saveTweets($tweets, $cm_cd_consulta_motor)
    {

        foreach ($tweets as $key => $tweet) {
            $this->insertTweetsConsulta($cm_cd_consulta_motor, $key, $tweet);
        }

    }

    public function saveTagResults($tweets_result_json, $cm_cd_consulta_motor)
    {
        $tweets_result = json_decode($tweets_result_json, true);

        $num = 1;
        foreach ($tweets_result as $tweet_result) {
            $queriesResult = new QueriesResult();
            $queriesResult->CM_CD_CONSULTA_MOTOR = $cm_cd_consulta_motor;

            foreach ($tweet_result as $key => $res) {
                if ($key == "conf") {
                    $queriesResult->RTC_CONFIANZA = (float)substr($res, 0, 5);
                    $queriesResult->TC_NUM_TWEET = $num;
                    $num = $num + 1;
                } else {
                    $queriesResult->RTC_PREDICION = $res;
                }
            }
            $queriesResult->save();
        }

    }

    public function getTweetsOfDoneQuerie($cm_cd_consulta_motor)
    {
        $querieTweets = QuerieTweets::where('CM_CD_CONSULTA_MOTOR', '=', $cm_cd_consulta_motor)->get();

        return $querieTweets;
    }

    public function convertQuerieTweetsArray($tweets)
    {

        $tweets_array = [];
        foreach ($tweets as $tweet) {
            $tweets_array[$tweet->TC_NUMERO_TWEET] = $tweet->TC_TEXTO_TWEET;
        }
        return $tweets_array;
    }
}
