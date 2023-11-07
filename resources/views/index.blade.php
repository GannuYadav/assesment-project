<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Task</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" />

</head>

<body>
    <div class="container px-4">
        <div class="col-xxl-12">
            @if (Session::has('success'))
                <div class="alert alert-success text-center" role="alert">
                    {{ Session::get('success') }}
                </div>
            @endif
            @if (Session::has('error'))
                <div class="alert alert-danger text-center" role="alert">
                    {{ Session::get('error') }}
                </div>
            @endif
            <div class="row gx-5">
                <div class="col-md-6 p-3 border bg-light">
                    <h4>Bucket Form</h4>
                    <form method="POST" action="{{ route('bucket-save') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="bucket_name" class="form-label">Name</label>
                            <span class="text-danger">*</span>
                            <input type="text" name="bucket_name"
                                class="form-control @error('bucket_name') is-invalid @enderror" id="bucket_name">
                            @if ($errors->has('bucket_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('bucket_name') }}
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="bucket_volume" class="form-label">Volume</label>
                            <span class="text-danger">*</span>
                            <input type="number" name="bucket_volume"
                                class="form-control @error('bucket_volume') is-invalid @enderror" id="bucket_volume">
                            @if ($errors->has('bucket_volume'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('bucket_volume') }}
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-sm btn-warning w-50 rounded">SAVE</button>
                    </form>
                </div>
                <div class="col-md-6 p-3 border bg-light">
                    <h4>Ball Form</h4>
                    <form method="POST" action="{{ route('ball-type-save') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="ball_name" class="form-label">Name</label><span class="text-danger">*</span>
                            <input type="text" name="ball_name"
                                class="form-control @error('ball_name') is-invalid @enderror" id="ball_name">
                            @if ($errors->has('ball_name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('ball_name') }}
                                </div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="ball_volume" class="form-label">Volume</label><span class="text-danger">*</span>
                            <input type="number" name="ball_volume"
                                class="form-control @error('ball_volume') is-invalid @enderror" id="ball_volume">
                            @if ($errors->has('ball_volume'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('ball_volume') }}
                                </div>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-sm btn-warning w-50 rounded">SAVE</button>
                    </form>
                </div>
            </div>
            <div class="row gx-5">
                <div class="col-md-6 p-3 border bg-light">

                    <h4>Bucket Suggestion</h4>
                    <form method="POST" action="{{ route('ball-save') }}">
                        @csrf
                        @foreach ($ball_results as $key => $item)
                            <div class="mb-3">
                                <input type="hidden" name="balls[{{ $key }}][name]" value="{{ $item->name }}">
                                <input type="hidden" name="balls[{{ $key }}][volume]" value="{{ $item->volume }}">
                                <label for="name_{{ $key }}" class="form-label">{{ $item->name }}</label>
                                <input type="number" class="form-control" name="balls[{{ $key }}][numberOfBalls]"
                                    id="name_{{ $key }}">
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-sm btn-warning w-50 rounded">SAVE</button>
                    </form>
                </div>
                <div class="col-md-6 p-3 border bg-light">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Result</h5>
                            <p class="card-text">Following are the suggested buckets:</p>
                            <div id="all_data"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script>

    let balls = @json($numberOfBalls);
    let temp = true;
    let temp2 = true;
    ansTrack = {};

    // Genrating answer with Empty Values.
    for (let ball of balls) {
        ansTrack[ball.name] = 0;
    }

    // Using Recursion and Dynamic Programming to find the most optimal way.
    // Using Include/Exlclude Algorithum to genrate the answer.
    function solve(balls, bucketSize, ballsTrack, dp) {

        // Base case.
        if (bucketSize == 0) return 0;
        if (bucketSize < 0) return Infinity;

        // DP Optimizaion
        if (dp[bucketSize] != -1) return dp[bucketSize];

        let mini = Infinity;
        // Going through every ball set to genrate most optimal answer.
        for (let i = 0; i < balls.length; i = i + 1) {
            if (bucketSize - parseInt(balls[i].volume) >= 0 && balls[i].numberOfBalls > 0) {
                ballsTrack[balls[i].name] = ballsTrack[balls[i].name] + 1;

                balls[i].numberOfBalls = parseInt(balls[i].numberOfBalls) - 1;
                // Recursion Call after decreasing current bucket Size.
                let ans = solve(balls, bucketSize - parseInt(balls[i].volume), ballsTrack, dp);

                // Backtracking data to next recursive iteration.
                balls[i].numberOfBalls = parseInt(balls[i].numberOfBalls) + 1;

                if (ans != Infinity) {
                    // Comparing previous Answers with new optimal Answers
                    if (ans + 1 < mini) {
                        if (temp == true) {
                            Object.assign(ansTrack, ballsTrack);
                            temp = false;
                        }
                        mini = ans + 1;
                    }
                } else {
                    // For Emergency Purpose when We can't fully fill the bucket.
                    if (temp2) {
                        Object.assign(ansTrack, ballsTrack);
                        temp2 = false;
                    }
                }

                // Backtracking
                ballsTrack[balls[i].name] = ballsTrack[balls[i].name] - 1;
            }

        }

        // Memoizing the data.
        return dp[bucketSize] = mini;
    }

    // Function that will call the recursion function to genrate the optimal answer.
    function coinChange(balls, bucketSize) {
        ballsTrack = {};
        for (let ball of balls) {
            ballsTrack[ball.name] = 0;
        }

        // 1D Dynamic Programming Array to improve time complexity and space complexity of the code.
        let dp = new Array(bucketSize + 1).fill(-1);

        let ans = solve(balls, bucketSize, ballsTrack, dp);
        return ans;

    }
    let bucket_results = @json($bucket_results);

    // genrating answer for every bucket one by one and remove balls that used for current bucket form balls array of object.
    bucket_results.forEach(bucket => {
        coinChange(balls, parseInt(bucket.volume));
        const keys = Object.keys(ansTrack);

        // changing balls count that used.
        keys.forEach((key, index) => {
            for(let i = 0;i < balls.length;i++){
                if(balls[i]['name'] == key){
                    balls[i]['numberOfBalls'] = balls[i]['numberOfBalls'] - ansTrack[key];
                }
            }
        });

        // genrating final answer string.
        ans = "For Bucket  <b>" + bucket.name + "</b> : ";

        keys.forEach((key,index)=>{
            if(ansTrack[key] != 0){
                ans = ans + " Place " + ansTrack[key] + " " + key + " Ball,";
            }
        })
        console.log(ans.slice(0, -1))
        $('#all_data').append(ans.slice(0, -1) + "<br />");

        // Reseting Variables for next bucket.
        for (let ball of balls) {
            ansTrack[ball.name] = 0;
        }
        temp = true;
        temp2 = true;

    });

</script>

</html>
