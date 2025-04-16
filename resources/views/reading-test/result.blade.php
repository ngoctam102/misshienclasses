<x-app-layout>
    <div class="max-w-4xl mx-auto py-8">
        <h1 class="text-3xl font-bold mb-6">Kết quả bài thi: {{ $test->title }}</h1>
        <div class="mb-4">
            <p class="text-lg">Điểm band: <span class="font-semibold">{{ $bandScore }}</span></p>
        </div>
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="w-1/12 px-4 py-2">STT</th>
                        <th class="w-7/12 px-4 py-2">Câu hỏi</th>
                        <th class="w-2/12 px-4 py-2">Trả lời của bạn</th>
                        <th class="w-2/12 px-4 py-2">Đáp án đúng</th>
                        <th class="w-2/12 px-4 py-2">Kết quả</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($results as $index => $result)
                    <tr class="{{ $result['isCorrect'] ? 'bg-green-100' : 'bg-red-100' }}">
                        <td class="border px-4 py-2">{{ $index + 1 }}</td>
                        <td class="border px-4 py-2">{!! $result['question']->question_content !!}</td>
                        <td class="border px-4 py-2">{{ $result['userAnswer'] ?? 'Không trả lời' }}</td>
                        <td class="border px-4 py-2">{{ $result['correctAnswer'] }}</td>
                        <td class="border px-4 py-2">{{ $result['isCorrect'] ? 'Đúng' : 'Sai' }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="border px-4 py-2 text-sm text-gray-600">Giải thích: {!! $result['explanation'] !!}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout> 