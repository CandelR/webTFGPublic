<?php


namespace App\Http\Controllers;


use App\Charts\HighCharts;
use App\EngineQueries;
use App\QueriesResult;
use App\QuerieTweets;
use Illuminate\Http\Request;

class StatisticsController extends Controller
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
        return view('statistics', ['chartsExec' => "", 'userQueries' => $userQueries]);
    }

    public function viewStatistics(Request $request)
    {
        $queries_done = $request->get('queries_done');
        $queries_done2 = $request->get('queries_done2');
        $secondQuerie = $request->get('secondQuerie');

        $engineQueries = new EngineQueries();
        $userQueries = $engineQueries->getMyHistory();

        $tags = $this->getResultOfQuerie($queries_done);
        $tweets_done = $this->getTweetsOfDoneQuerie($queries_done);
        $tweets_done = $this->convertQuerieTweetsArray($tweets_done);
        $engineQuerie = EngineQueries::where('CM_CD_CONSULTA_MOTOR', '=', $queries_done)->first();
        $keyword = $engineQuerie->CM_PALABRA_BUSQUEDA;
        $ntweets = $engineQuerie->CM_NUMERO_TWEETS;
        $res = $this->createHighChart($tags, $queries_done);
        $chart = $res[0];
        $resultados_analisis = $res[1];
        $noResults = count($resultados_analisis) == 0 ? true : false;
        $res = [
            'chart' => $chart,
            'chartsExec' => 'ok',
            'userQueries' => $userQueries,
            'resultados_analisis' => $resultados_analisis,
            'ntweets' => $ntweets,
            'keyword' => $keyword,
            'tweets' => $tweets_done,
            'noResults' => $noResults,
            'nConsulta' => $queries_done,
            'nConsulta2' => false];

        if ($secondQuerie) {
            $tags2 = $this->getResultOfQuerie($queries_done2);
            $tweets_done2 = $this->getTweetsOfDoneQuerie($queries_done2);
            $tweets_done2 = $this->convertQuerieTweetsArray($tweets_done2);
            $engineQuerie2 = EngineQueries::where('CM_CD_CONSULTA_MOTOR', '=', $queries_done2)->first();
            $keyword2 = $engineQuerie2->CM_PALABRA_BUSQUEDA;
            $ntweets2 = $engineQuerie2->CM_NUMERO_TWEETS;
            $res2 = $this->addSecondDataSetToHighChart($tags2, $queries_done2, $chart);
            $chart = $res2[0];
            $resultados_analisis2 = $res2[1];
            $noResults2 = count($resultados_analisis2) == 0 ? true : false;
            $res = [
                'chart' => $chart,
                'chartsExec' => 'ok',
                'userQueries' => $userQueries,
                'resultados_analisis' => $resultados_analisis,
                'ntweets' => $ntweets,
                'keyword' => $keyword,
                'tweets' => $tweets_done,
                'noResults' => $noResults,
                'nConsulta' => $queries_done,
                'resultados_analisis2' => $resultados_analisis2,
                'ntweets2' => $ntweets2,
                'keyword2' => $keyword2,
                'tweets2' => $tweets_done2,
                'noResults2' => $noResults2,
                'nConsulta2' => $queries_done2];
        }

        return view('statistics')->with($res);

    }


    public function createHighChart($json_result_tweets, $cm_cd_consulta_motor)
    {
        $tweets_json_decoded = json_decode($json_result_tweets, true);
        $chart = new HighCharts();

        $porcentaje_confianza = [];
        $contador = [];
        $count = 1;
        $resultado_analisis = [];

        foreach ($tweets_json_decoded as $tweets_result) {
            $porcentaje_confianza[] = (float)$tweets_result['RTC_CONFIANZA'];
            $contador[] = $count;
            $count = $count + 1;
            $resultado_analisis[] = $tweets_result['RTC_PREDICION'];
        }

        $chart->labels($contador);
        $chart->dataset('Consulta nº ' . $cm_cd_consulta_motor, 'line', $porcentaje_confianza);

        return [$chart, $resultado_analisis];
    }

    public function addSecondDataSetToHighChart($json_result_tweets, $cm_cd_consulta_motor, $chart)
    {
        $tweets_json_decoded = json_decode($json_result_tweets, true);

        $porcentaje_confianza = [];
        $contador = [];
        $count = 1;
        $resultado_analisis = [];

        foreach ($tweets_json_decoded as $tweets_result) {
            $porcentaje_confianza[] = (float)$tweets_result['RTC_CONFIANZA'];
            $contador[] = $count;
            $count = $count + 1;
            $resultado_analisis[] = $tweets_result['RTC_PREDICION'];
        }

        $chart->labels($contador);
        $chart->dataset('Consulta nº ' . $cm_cd_consulta_motor, 'line', $porcentaje_confianza);

        return [$chart, $resultado_analisis];
    }


    public function getResultOfQuerie($cm_cd_consulta_motor)
    {
        $resultOfTweets = QueriesResult::where('CM_CD_CONSULTA_MOTOR', '=', $cm_cd_consulta_motor)->get();

        return $resultOfTweets;
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
