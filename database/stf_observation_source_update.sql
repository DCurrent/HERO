USE [EHSINFO]
GO
/****** Object:  StoredProcedure [dbo].[stf_observation_source_update]    Script Date: 5/22/2017 10:20:14 AM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

-- Create date: 2015-07-27
-- Description:	Insert or update items.
-- =============================================
ALTER PROCEDURE [dbo].[stf_observation_source_update]
	
	-- Parameters
	@param_id_list			xml				= NULL, 
	@param_update_by		int				= NULL,
	@param_update_host		varchar(50)		= NULL,
	@param_label			varchar(50)		= NULL,
	@param_details			varchar(max)	= NULL,
	@param_observation		varchar(max)	= NULL,
	@param_solution			varchar(max)	= NULL		

AS
BEGIN
	
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;	

	-- Local cache of master result.
	CREATE TABLE #cache_master_update_result
	(
		id_row	int,
		id_key	int,
		id		int
	)

	-- Update master table. This creates a new
	-- version entry and primary key we need.
		INSERT INTO #cache_master_update_result
			EXEC stf_master_update
				@param_id_list,
				@param_update_by,
				@param_update_host

	-- Update data table using the
	-- new primary key from master table.
		INSERT INTO tbl_stf_observation_source
				(id_key,
				label,
				details,
				observation,
				solution)	

		SELECT _master.id_key,
				@param_label,
				@param_details,
				@param_observation,
				@param_solution
		FROM 
			#cache_master_update_result AS _master

	-- Sub Data
		-- None

	-- Output ID of the newly inserted record.
	SELECT TOP 1
		_master.id
		FROM #cache_master_update_result AS _main
		JOIN tbl_stf_master AS _master ON _main.id_key = _master.id_key
			
					
END

