<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use App\Models\ServiceRequest;

class ProviderHomeController extends Controller
{
    public function index()
    {
        App::setLocale('pt_BR');

        $tips = [
            "💡 Dica: Perfis com foto e descrição detalhada recebem até 3x mais pedidos. Mostre quem você é!",
            "💬 Dica: Responda rapidamente aos clientes. Agilidade gera confiança e aumenta suas chances de fechar negócio.",
            "⭐ Dica: Peça avaliações aos clientes. Quanto mais avaliações positivas, maior a sua visibilidade!",
            "🎯 Dica: Escolha bem suas categorias. Elas definem onde e como seu perfil será exibido nas buscas.",
            "🛠️ Dica: Atualize seu portfólio com frequência. Mostre seus melhores trabalhos e conquiste novos clientes.",
            "👀 Dica: Use uma foto de perfil profissional. A primeira impressão conta (e muito).",
            "📍 Dica: Mantenha seu endereço atualizado. Assim, você aparece para quem realmente pode contratar seus serviços.",
            "📆 Dica: Indique sua disponibilidade corretamente. Isso evita pedidos em horários que você não pode atender.",
            "📢 Dica: Divulgue seu perfil fora da plataforma. Compartilhe o link nas redes sociais e no WhatsApp!",
            "📝 Dica: Preencha todas as informações do perfil. Um perfil completo gera mais confiança no cliente.",
            "📷 Dica: Adicione imagens reais de serviços feitos por você. Isso mostra experiência e profissionalismo.",
            "🧑‍💼 Dica: Seja educado e cordial no chat. Um bom atendimento começa na conversa!",
            "🏅 Dica: Aceite avaliações mesmo que não sejam 5 estrelas. Aprenda com elas e melhore cada vez mais.",
            "📈 Dica: Preste atenção nas métricas da sua conta. Elas mostram onde você pode melhorar.",
            "🔄 Dica: Atualize sua descrição conforme seus serviços evoluem. Mantenha o texto sempre atual e relevante.",
            "🎨 Dica: Capriche na escrita! Evite erros de português e seja claro ao descrever seus serviços.",
            "⏱️ Dica: Não deixe pedidos pendentes por muito tempo. Clientes buscam quem responde rápido.",
            "🔧 Dica: Ofereça garantias ou diferenciais. Isso te destaca da concorrência.",
            "📊 Dica: Clientes confiam mais em quem tem avaliações e portfólio. Não fique de fora!",
            "💎 Dica: Seja profissional do começo ao fim. Pontualidade, respeito e compromisso fidelizam clientes."
        ];

        $randomTip = $tips[array_rand($tips)];
        $provider = Auth::user();

        // Avaliações
        $avgRating = $provider->reviews->avg('rating');
        $totalReviews = $provider->reviews->count();
        $avgRatingFormatted = $avgRating ? number_format($avgRating, 1) : null;

        // Pedidos recebidos
        $totalRequests = ServiceRequest::where('provider_id', $provider->id)->count();

        // Últimas 3 avaliações
        $latestReviews = $provider->reviews()
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        // ===== PERFIL COMPLETO =====

        // Início com 25% (cadastro obrigatório)
        $profileCompletion = 25;

        // Verificações adicionais
        $hasAddress = $provider->addresses()->exists();
        $hasPortfolio = $provider->portfolios()->exists();
        $hasCategory = $provider->categories()->count() > 0;

        if ($hasAddress) $profileCompletion += 25;
        if ($hasPortfolio) $profileCompletion += 25;
        if ($hasCategory) $profileCompletion += 25;

        // Mensagem dinâmica com os campos faltantes
        $missing = [];
        if (!$hasAddress) $missing[] = 'endereço';
        if (!$hasPortfolio) $missing[] = 'portfólio';
        if (!$hasCategory) $missing[] = 'categoria de serviço';

        $missingItemsMessage = '';
        if (count($missing) > 0) {
            $missingItemsMessage = 'Adicione ' . implode(', ', $missing) . '!';
        }

        return view('providers.home', compact(
            'randomTip',
            'avgRatingFormatted',
            'totalReviews',
            'totalRequests',
            'latestReviews',
            'profileCompletion',
            'missingItemsMessage'
        ));
    }
}