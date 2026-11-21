<?php
/**
 * Template Name: Common Facility Centre
 *
 * Client letter §3. The client calls the CFC one of their most important
 * projects and asked that it be presented as shared infrastructure supporting
 * other farmer entrepreneurs and small-scale processors — not merely as a
 * facility for their own manufacturing.
 *
 * It is also, in their words, *planned*: "The CFC is planned to provide...".
 * Every heading, pill and disclosure on this page holds that tense. Do not
 * rewrite them into the present until the facility is commissioned, and then
 * only service by service.
 *
 * @package Guruswad_Ocean
 */

defined( 'ABSPATH' ) || exit;
get_header();
the_post();

gor_page_hero(
	array(
		'eyebrow' => __( 'Common Facility Centre', 'guruswad' ),
		'title'   => __( 'Shared infrastructure, not a private <em>factory</em>.', 'guruswad' ),
		'lead'    => __( 'A common processing facility planned for farmers, producer companies, women’s self-help groups and small food entrepreneurs — so that a small producer can reach a market without building a plant first.', 'guruswad' ),
		'variant' => 'cfc',
	)
);

$gor_serves = array(
	array( 'farmer', __( 'Farmers', 'guruswad' ), __( 'Members of the producer network, and growers outside it who need cleaning or milling done properly rather than roughly.', 'guruswad' ) ),
	array( 'handshake', __( 'Farmer Producer Organisations', 'guruswad' ), __( 'Other FPOs with produce to aggregate and no processing line of their own.', 'guruswad' ) ),
	array( 'women', __( 'Women’s self-help groups', 'guruswad' ), __( 'Groups taking on cleaning, grading, value addition and packing as paid work.', 'guruswad' ) ),
	array( 'shop', __( 'Small entrepreneurs', 'guruswad' ), __( 'Small-scale food processors who need plant capacity, not a plant — and a product they can sell under their own name.', 'guruswad' ) ),
);

// The client's own six service headings, in their order.
$gor_services = array(
	array( 'grain', __( 'Grain cleaning and grading', 'guruswad' ), __( 'De-stoning, cleaning and grading by size and quality — the step that decides whether a lot can be sold at all.', 'guruswad' ) ),
	array( 'factory', __( 'Millet processing', 'guruswad' ), __( 'De-hulling and milling for sorghum, pearl millet and the other millets the network grows.', 'guruswad' ) ),
	array( 'spark', __( 'Jowar puffs and other value-added products', 'guruswad' ), __( 'Puffing and the value-added lines that turn a grain worth a commodity price into a product worth a shelf price.', 'guruswad' ) ),
	array( 'cart', __( 'Flour, rava and instant mixes', 'guruswad' ), __( 'Milling and blending into flour, rava and instant mixes, in pack formats a small producer can actually sell.', 'guruswad' ) ),
	array( 'shield', __( 'Packaging and quality testing', 'guruswad' ), __( 'Packing to retail and bulk formats, with testing against the standard the buyer is going to ask about.', 'guruswad' ) ),
	array( 'flask', __( 'Product development and processing training', 'guruswad' ), __( 'Developing new formats, and training the people who will make them — so the skill stays in the village after the batch has left it.', 'guruswad' ) ),
);
?>

<section class="section" id="what">
	<div class="container split">
		<div>
			<span class="status-pill status-pill--planned" data-reveal><?php esc_html_e( 'Planned project', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'One facility, used by everyone who needs it.', 'guruswad' ); ?></h2>
			<p class="lead" data-reveal><?php esc_html_e( 'Most small millet producers lose their margin in the same place. They can grow the grain, but they cannot clean, grade, mill or pack it to the standard a buyer expects. Building a line of your own for a few tonnes a season makes no sense. Sharing one does.', 'guruswad' ); ?></p>
			<p data-reveal><?php esc_html_e( 'The Common Facility Centre is planned as that shared line — held through Guruswad Agriculture and Food Association, and open to producers beyond our own network. The same equipment that makes a MilletRush product is intended to make someone else’s.', 'guruswad' ); ?></p>
			<?php
			gor_disclosure(
				'flask',
				'<p><strong>' . esc_html__( 'This is a planned project.', 'guruswad' ) . '</strong> '
				. esc_html__( 'Nothing on this page describes a facility in operation, and none of the services listed is being offered yet. The page will be updated service by service as each line is commissioned, so that what it claims and what exists stay the same thing.', 'guruswad' )
				. '</p>'
			);
			?>
		</div>
		<div><?php gor_art_panel( 'factory', __( 'Planned site of the Common Facility Centre, Maharashtra', 'guruswad' ), get_post_thumbnail_id() ); ?></div>
	</div>
</section>

<section class="section section--cream2" id="who">
	<div class="container">
		<div class="section-head">
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Who it is for', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'Four kinds of producer, one shared line.', 'guruswad' ); ?></h2>
			<p class="lead" data-reveal><?php esc_html_e( 'The facility is not being built for one company’s production run. It is being built as infrastructure — and infrastructure is judged by who else can use it.', 'guruswad' ); ?></p>
		</div>
		<div class="cards-4">
			<?php foreach ( $gor_serves as $gor_s ) : ?>
				<div class="card" data-reveal>
					<span class="card__icon"><?php gor_the_icon( $gor_s[0] ); ?></span>
					<h3><?php echo esc_html( $gor_s[1] ); ?></h3>
					<p><?php echo esc_html( $gor_s[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section" id="services">
	<div class="container">
		<div class="section-head">
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Services planned', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'From a sack of raw grain to a sealed, tested pack.', 'guruswad' ); ?></h2>
			<p class="lead" data-reveal><?php esc_html_e( 'Six services, each one a step a small producer currently has to skip, outsource at a loss, or do badly.', 'guruswad' ); ?></p>
		</div>
		<div class="cards-3">
			<?php foreach ( $gor_services as $gor_s ) : ?>
				<div class="card" data-reveal>
					<span class="card__icon"><?php gor_the_icon( $gor_s[0] ); ?></span>
					<h3><?php echo esc_html( $gor_s[1] ); ?></h3>
					<p><?php echo esc_html( $gor_s[2] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
		<?php
		gor_disclosure(
			'doc',
			'<p>' . esc_html__( 'Equipment specifications, installed capacity, tariffs and the commissioning timetable are to be confirmed by the client before publication. The six services above are the scope as the client set it out; the numbers behind them are not yet ours to publish.', 'guruswad' ) . '</p>'
		);
		?>
	</div>
</section>

<section class="section section--dark" id="why">
	<div class="container split split--wide-left">
		<div>
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Why shared', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'Processing is where a farmer’s margin is usually lost.', 'guruswad' ); ?></h2>
			<p class="lead" data-reveal><?php esc_html_e( 'A grower who sells raw grain takes the commodity price. A grower who sells cleaned, graded, milled and packed grain takes considerably more. The difference is not the crop — it is access to a machine.', 'guruswad' ); ?></p>
		</div>
		<ul class="check-list" data-reveal>
			<li><?php esc_html_e( 'Capacity a small producer could not justify alone', 'guruswad' ); ?></li>
			<li><?php esc_html_e( 'One quality standard, whoever the batch belongs to', 'guruswad' ); ?></li>
			<li><?php esc_html_e( 'Training that stays with the people, not the plant', 'guruswad' ); ?></li>
			<li><?php esc_html_e( 'Women’s self-help groups paid for processing work, not only for labour', 'guruswad' ); ?></li>
			<li><?php esc_html_e( 'A route to market for producers who are not our members', 'guruswad' ); ?></li>
		</ul>
	</div>
</section>

<section class="section" id="use">
	<div class="container">
		<div class="section-head section-head--center">
			<span class="eyebrow" data-reveal><?php esc_html_e( 'Register your interest', 'guruswad' ); ?></span>
			<h2 class="h-lg" data-split><?php esc_html_e( 'If you would use it, tell us now.', 'guruswad' ); ?></h2>
			<p class="lead" data-reveal><?php esc_html_e( 'Commissioning priorities are being set around real demand. If you are a farmer, an FPO, a self-help group or a small processor who would use the facility, say what you need and in what volume — it affects what gets installed first.', 'guruswad' ); ?></p>
			<div class="btn-row" style="justify-content:center" data-reveal>
				<a class="btn btn--gold" href="<?php echo esc_url( gor_enquiry_url( 'cfc' ) ); ?>" data-magnetic><?php esc_html_e( 'CFC enquiry', 'guruswad' ); ?> <?php gor_the_icon( 'arrow' ); ?></a>
				<?php
				$gor_farmers = gor_org_link( 'farmers' );
				if ( $gor_farmers ) :
					?>
					<a class="btn btn--ghost" href="<?php echo esc_url( $gor_farmers ); ?>"><?php esc_html_e( 'Farmers & FPO network', 'guruswad' ); ?></a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<?php if ( get_the_content() ) : ?>
	<section class="section"><div class="container entry-content"><?php the_content(); ?></div></section>
<?php endif; ?>

<?php
gor_cta_band();
get_footer();
