USE [EHSINFO]
GO
/****** Object:  StoredProcedure [dbo].[stf_observation_source_read]    Script Date: 5/24/2017 4:04:34 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- Create date: 2015-07-27
-- Description:	Get single record detail.
-- =============================================

CREATE PROCEDURE [dbo].[stf_observation_source_read]
	
	-- filter
	@param_filter_id		int	= NULL,
	@param_filter_id_key	int = NULL
		
AS	
	SET NOCOUNT ON;

	-- Create and populate the main data cache. This is 
	-- where we will do most (if not all) of our JOINs, 
	-- sorting and filtering to create a complete record set of
	-- primary data for consumption. We use a temporary
	-- table for performance and convenience. This temp table
	-- is also available in any other procedures we might call
	-- while this one is running. If we remember to use a 
	-- consistent naming convention, that will in turn allow us 
	-- to encapsulate a lot of repetitive work into reusable sub 
	-- procedures and keep their parameters to a bare minimum.
		SELECT			
				_master.id, 
				_master.id_key,
				_master.create_time,
				_master.update_time,
				_main.label,
				_main.details,
				_main.observation,
				_main.solution,
				_main.status,
				_master.active	
		INTO #cache_primary					
		FROM dbo.tbl_stf_observation_source AS _main
			JOIN tbl_stf_master _master ON _main.id_key = _master.id_key 
		WHERE
			-- Normal filter. This produces an active 
			-- revision list of all records.
			(@param_filter_id_key IS NULL AND _master.active = 1)
			OR
			-- Key filter. Get a specfic revision 
			-- of record by its ID key.
			(_master.id_key = @param_filter_id_key)			
		ORDER BY _main.label

	
			
	-- Navigation. This executes the navigation
	-- procedure, which produces a recordset
	-- including next ID, last ID, etc. for
	-- use by the control code to create record
	-- navigation buttons. See the stored 
	-- procedure for details.
		EXEC master_navigation @param_filter_id

	-- Select and output recordsets of data.

		-- Main (primary) data. We've already done all of
		-- the data processing. Just output the recordset
		-- filtered with ID.
		SELECT
			* 
		FROM 
			#cache_primary AS _data
		WHERE _data.id = @param_filter_id	
	

	-- Subsets. Once all the work is done for our primary table 
		-- None


