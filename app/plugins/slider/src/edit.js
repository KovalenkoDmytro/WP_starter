/**
 * Retrieves the translation of text.
 */
import {__} from '@wordpress/i18n';

/**
 * WordPress Block Editor Components.
 */
import {
	InspectorControls, MediaUpload,
	PanelColorSettings,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { Swiper, SwiperSlide } from 'swiper/react';

import {
	PanelBody,
	RangeControl,
	__experimentalNumberControl as NumberControl, Button,
} from '@wordpress/components';
import 'swiper/css';


/**
 * Edit function for the block.
 *
 * @param {Object} props               Properties passed to the function.
 * @param {Object} props.attributes    Available block attributes.
 * @param {Function} props.setAttributes Function to update block attributes.
 */
export default function Edit({attributes, setAttributes}) {
	const {textColor, fontSize, slidesPerView, media} = attributes;
	const ALLOWED_MEDIA_TYPES = ['image', 'video', 'audio'];


	return (
		<div {...useBlockProps()}>
			{/* Block Content */}

			<InspectorControls>

				<PanelBody title={__('Font Settings', 'copyright-date-block')} initialOpen={true}>


					<NumberControl
						label={__('Slides Per View', 'copyright-date-block')}
						value={slidesPerView}
						onChange={(value) => {
							setAttributes({slidesPerView: Number(value)});
						}}
						isShiftStepEnabled={true}
						shiftStep={1}
						min={1}
						max={2000}
					/>
				</PanelBody>
				<PanelColorSettings
					title={__('Text Color', 'copyright-date-block')}
					colorSettings={[
						{
							value: textColor,
							onChange: (newColor) => setAttributes({textColor: newColor}),
							label: __('Text Color', 'copyright-date-block'),
						},
					]}
				/>
			</InspectorControls>

			<MediaUpload
				onSelect={(media) => {
					setAttributes({media: media});
				}}
				value={media.map(item => item.id)}
				multiple={true}
				allowedTypes={ALLOWED_MEDIA_TYPES}
				render={({open}) => (
					<Button onClick={open} className="components-button is-primary">
						'Open Media Library'
					</Button>
				)}
			/>


			{media.length > 0 && (
				<Swiper slidesPerView={slidesPerView || 1} spaceBetween={10}>
					{media.map((item) => (
						<SwiperSlide key={item.id}>
							<img
								src={item.url}
								alt={item.alt || __('Media preview', 'copyright-date-block')}
								style={{ width: '100%' }}
							/>
						</SwiperSlide>
					))}
				</Swiper>
			)}

		</div>
	);
}