<x-app-layout>
    <div class="fixed top-16 right-4 bg-white p-4 rounded-lg shadow-lg z-50">
        <div class="text-center">
            <div class="text-2xl font-bold text-gray-800" id="countdown">{{ $test->duration }} phút</div>
            <div class="text-sm text-gray-600">Thời gian còn lại</div>
            <button id="startButton" class="mt-2 px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition duration-150 ease-in-out">
                Bắt đầu làm bài
            </button>
        </div>
        <div class="mt-2 text-center">
            <div class="text-lg font-semibold text-gray-800">
                <span id="answered-questions">0</span>/<span id="total-questions">{{ $test->total_questions }}</span> câu
            </div>
        </div>
    </div>

    <x-two-cols>
        <div class="h-[calc(100vh-4rem)] overflow-y-auto pr-4">
            @foreach($passages as $passage)
                <div class="passage leading-loose text-[18px] text-justify">
                    <h2 class="text-3xl font-bold mb-4 text-center bg-orange-500 text-black rounded-md flex justify-center items-center p-2">PASSAGE {{ $passage->order }}: {!! $passage->title !!}</h2>
                    <p class="mb-4">{!! $passage->content !!}</p>
                    <hr class="my-5 border-black">
                </div>
            @endforeach
        </div>
        @slot('aside')
        <div class="h-[calc(100vh-4rem)] overflow-y-auto p-2 leading-loose text-[18px]">
            <form id="testForm" action="{{ route('reading-test.submit', $test->slug) }}" method="POST">
                @csrf
                @foreach($passages as $passage)
                    @foreach($questionGroups->where('passage_id', $passage->id) as $group)
                        <div class="question-group mb-5">
                            <h3 class="text-lg font-bold my-5">{!! $group->description !!}</h3>
                            @if (isset($group->content))
                                <p class="mb-4">{!! $group->content !!}</p>
                            @endif
                            @foreach($questions->where('group_id', $group->id) as $question)
                                <div class="question mb-5" id="question-{{ $question->id }}">
                                    <div class="question-content">
                                        <div class="flex items-start gap-2">
                                            <span class="font-medium w-6">{{ $question->order }}.</span>
                                            <div class="flex-1">{!! $question->question_content !!}</div>  
                                        </div>
                                    </div>
                                    {{-- Hiển thị loại câu hỏi khác nhau --}}
                                    {{-- Fill in blank --}}
                                    @if($question->question_type == 'fill_in_blank')
                                        <div class="pl-3"><input class="border-1 border-gray-300 rounded-md pl-3 bg-white" type="text" name="answer[{{ $question->id }}]" onchange="markQuestionAsAnswered({{ $question->id }})" disabled /></div>
                                    {{-- Correct answer or Fill in blank with options --}}
                                    @elseif($question->question_type == 'correct_answer' ||$question->question_type == 'fill_in_blank_with_options')
                                        <select class="w-full max-w-[440px] border-1 border-gray-300 rounded-md bg-white" name="answer[{{ $question->id }}]" onchange="markQuestionAsAnswered({{ $question->id }})" disabled>
                                            <option value="">-- Chọn đáp án --</option>
                                            @foreach($questionOptions->where('question_id', $question->id)->sortBy('order') as $option)
                                                <option value="{{ $option->id }}">{{ $option->option_text }}</option>
                                            @endforeach
                                        </select>
                                    {{-- True false not given --}}
                                    @elseif($question->question_type == 'true_false_not_given')
                                        <select class="border-1 border-gray-300 rounded-md bg-white" name="answer[{{ $question->id }}]" onchange="markQuestionAsAnswered({{ $question->id }})" disabled>
                                            <option value="">-- Chọn đáp án --</option>
                                            <option value="true">True</option>
                                            <option value="false">False</option>
                                            <option value="not_given">Not Given</option>
                                        </select>
                                    {{-- Matching --}}
                                    @elseif($question->question_type == 'matching')
                                        @if (isset($matchingQuestions->where('question_id', $question->id)->first()->matching_text))
                                            <div class="matching-text mb-2">
                                                {!! $matchingQuestions->where('question_id', $question->id)->first()->matching_text !!}
                                            </div>
                                        @endif
                                        <select class="w-full max-w-[440px] border-1 border-gray-300 rounded-md bg-white" name="answer[{{ $question->id }}]" onchange="markQuestionAsAnswered({{ $question->id }})" disabled>
                                            <option value="">-- Chọn đáp án --</option>
                                            @foreach($matchingOptions as $option)
                                                <option value="{{ $option->id }}">{{ $option->option_text }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <hr class="my-5 border-black">
                    @endforeach
                @endforeach
                <div class="sticky bottom-0 left-0 right-0 bg-white p-4 border-t border-gray-200">
                    <button type="submit" id="submitButton" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-150 ease-in-out" disabled>
                        Nộp bài
                    </button>
                </div>
            </form>
        </div>
        @endslot
    </x-two-cols>

    <script>
        console.log('Script đã được tải');
        
        // Khởi tạo biến toàn cục
        let answeredQuestions = 0;
        const totalQuestions = {{ $test->total_questions }};
        let timeLeft = {{ $test->duration * 60 }}; // Chuyển phút thành giây
        let countdownInterval = null;
        let isTestStarted = false;

        // Hàm cập nhật countdown
        function updateCountdown() {
            console.log('Đang cập nhật countdown');
            const hours = Math.floor(timeLeft / 3600);
            const minutes = Math.floor((timeLeft % 3600) / 60);
            const seconds = timeLeft % 60;

            document.getElementById('countdown').textContent = 
                `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                alert('Hết giờ! Bài thi sẽ được nộp tự động.');
                document.getElementById('testForm').submit();
            }
            timeLeft--;
        }

        // Hàm đánh dấu câu hỏi đã trả lời
        function markQuestionAsAnswered(questionId) {
            console.log('Đánh dấu câu hỏi đã trả lời:', questionId);
            const questionElement = document.getElementById(`question-${questionId}`);
            const inputElement = questionElement.querySelector('input, select');
            
            if (inputElement.value && !questionElement.classList.contains('answered')) {
                questionElement.classList.add('answered');
                questionElement.style.backgroundColor = '#e6ffed';
                answeredQuestions++;
                document.getElementById('answered-questions').textContent = answeredQuestions;
            } else if (!inputElement.value && questionElement.classList.contains('answered')) {
                questionElement.classList.remove('answered');
                questionElement.style.backgroundColor = '';
                answeredQuestions--;
                document.getElementById('answered-questions').textContent = answeredQuestions;
            }
        }

        // Hàm bắt đầu bài thi
        function startTest() {
            console.log('Bắt đầu bài thi');
            if (!isTestStarted) {
                isTestStarted = true;
                document.getElementById('startButton').style.display = 'none';
                document.getElementById('submitButton').disabled = false;
                
                // Bật tất cả các input và select
                const inputs = document.querySelectorAll('input, select');
                inputs.forEach(input => {
                    input.disabled = false;
                });

                // Bắt đầu countdown
                countdownInterval = setInterval(updateCountdown, 1000);
                updateCountdown(); // Gọi lần đầu để hiển thị ngay lập tức
            }
        }

        // Gán sự kiện click cho nút bắt đầu
        document.getElementById('startButton').addEventListener('click', startTest);
        console.log('Đã gán sự kiện click cho nút bắt đầu');

        // Gán sự kiện click cho nút nộp bài
        document.getElementById('submitButton').addEventListener('click', function() {
            console.log('Nút nộp bài đã được nhấn');
        });

        // Lưu trạng thái đã trả lời khi tải trang
        const inputs = document.querySelectorAll('input, select');
        inputs.forEach(input => {
            const match = input.name.match(/\[(\d+)\]/);
            if (match && input.value) {
                const questionId = match[1];
                markQuestionAsAnswered(questionId);
            }
        });

        // Gán sự kiện submit cho form
        document.getElementById('testForm').addEventListener('submit', function(event) {
            console.log('Form đang được submit');
        });
    </script>
</x-app-layout>
