export default class AssetsLoader {
	getScriptElement( src ) {
		const scriptElement = document.createElement( 'script' );

		scriptElement.src = src;

		return scriptElement;
	}

	getStyleElement( src ) {
		const styleElement = document.createElement( 'link' );

		styleElement.rel = 'stylesheet';
		styleElement.href = src;

		return styleElement;
	}

	add( assetData ) {
		const { type, key, src, parent } = assetData;

		if ( ! this.constructor.assets[ type ][ key ] ) {
			this.constructor.assets[ type ][ key ] = {
				src,
				parent,
			};
		}
	}

	load( type, key ) {
		return new Promise( ( resolve ) => {
			const assetData = this.constructor.assets[ type ][ key ];

			if ( assetData.isLoaded ) {
				resolve( true );

				return;
			}

			let element;

			if ( 'scripts' === type ) {
				element = this.getScriptElement( assetData.src );
			} else if ( 'styles' === type ) {
				element = this.getStyleElement( assetData.src );
			}

			element.onload = () => {
				this.constructor.assets[ type ][ key ].isLoaded = true;

				resolve( true );
			};

			const parent = [ 'body', 'head' ].includes( assetData.parent ) ? assetData.parent : 'body';

			document[ parent ].appendChild( element );
		} );
	}
}

const fileSuffix = elementorFrontendConfig.environmentMode.isScriptDebug ? '' : '.min';

AssetsLoader.assets = {
	scripts: {},
	styles: {},
};
